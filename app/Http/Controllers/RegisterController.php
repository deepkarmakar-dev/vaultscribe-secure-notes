<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Cache; // Weak password check ke liye required hai

class RegisterController extends Controller
{
    // show register page 
    public function register()
    {
        return view('register');
    }

    // handle form 
    public function store(Request $req)
    {
        // 1. PasswordController se exact same validation rules match kiye
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
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

        // duplicate check
        if (User::where('email', $req->email)->exists()) {
            return back()->withErrors(['email' => 'invalid credentials']);
        }

        // 2. PasswordController wala exact same Weak Password Check yahan bhi laga diya
        $passwordLower = strtolower($req->password);
        $weakPasswords = Cache::remember('weak_passwords', 86400, function () {
            $path = storage_path('app/weak_passwords.txt');
            return is_readable($path) ? array_map('trim', file($path, FILE_IGNORE_NEW_LINES)) : [];
        });

        if (in_array($passwordLower, $weakPasswords)) {
            return back()->withErrors(['password' => 'This password is too common.']);
        }

        // generate OTP
        $otp = random_int(100000, 999999);

        // 3. Pepper logic (Jaise aapne setup kiya tha)
        $pepperedPassword = hash_hmac(
            'sha256',
            $req->password,
            config('app.pepper')
        );

        session([
            'pending_user' => [
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($pepperedPassword), 
            ],
            'registration_otp' => Hash::make($otp),
            'otp_attempts' => 0,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // send mail
        Mail::raw("Your OTP is: $otp", function ($message) use ($req) {
            $message->to($req->email)
                ->subject('Your OTP Code');
        });

        return redirect()->route('otp.verify')
            ->with('success', 'OTP sent to your email');
    }
}