<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
class registerController extends Controller
{
  public function register()
    {
       
        return view('register');
    }

   public function store(Request $request)
{
    // 1. Validation
    $request->validate([
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => [
            'required',
            'confirmed',
            'min:10',
            'regex:/[A-Z]/', 
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*?&]/',
        ],
    ]);

    // 2. Weak Password Check (Optimized)
    $passwordLower = strtolower($request->password);
    $weakPasswords = Cache::remember('weak_passwords', 86400, function () {
        $path = storage_path('app/weak_passwords.txt');
        return is_readable($path) ? array_map('trim', file($path, FILE_IGNORE_NEW_LINES)) : [];
    });

    if (in_array($passwordLower, $weakPasswords)) {
        return back()->withErrors(['password' => 'This password is too common.']);
    }

    // 3. Create User with Pepper
    // Note: Ensure HASH_PEPPER is set in .env
    $pepperedPassword = hash_hmac('sha256', $request->password, config('app.pepper', env('HASH_PEPPER')));
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($pepperedPassword),
    ]);

    // 4. Handle OTP
    $otp = random_int(100000, 999999);
    
    $user->update([
        'otp' => Hash::make($otp), // Secure, but ensure you use Hash::check in the verify step
        'otp_expires_at' => now()->addMinutes(5),
    ]);

    // 5. Send Notification
    // Pro-tip: Use a Mailable class for better formatting and queueing
    Mail::raw("Your OTP is: $otp. It expires in 5 minutes.", function ($message) use ($user) {
        $message->to($user->email)->subject('Verify Your Account');
    });

    session(['otp_email' => $user->email]);

    return redirect()->route('otp.verify');
}
}