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



class PasswordController extends Controller
{


    public function forget()
    {
        return view('forgetpassword');
    }

   public function forgetpass(Request $req)
{
    // 1️ Validation
    $req->validate([
        'email' => 'required|email'
    ]);

    //  Reset Link Send
    $status = Password::sendResetLink(
        $req->only('email')
    );

    // 3 Response Handle
    if ($status === Password::RESET_LINK_SENT) {
        return back()->with('status', 'Reset link sent to your email');
    } else {
        return back()->withErrors(['email' => 'Email not found']);
    }
}

public function showResetForm(Request $req, $token)
{
    return view('passwordreset', [
        'token' => $token,
        'email' => $req->email
    ]);
}
public function resetPassword(Request $request)
{
     $attempts = session()->get('login_attempts', 0);

    $request->validate([
    'email' => 'required|string|email|max:255|exists:users,email',
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

   
       if (!$request->filled('g-recaptcha-response')) {
            return back()->withErrors([
                'email' => 'Captcha required'
            ]);
        }

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('NOCAPTCHA_SECRET'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]
        );

        if (!$response->json('success')) {
            return back()->withErrors([
                'email' => 'Captcha failed'
            ]);
        }


    //  Password Reset Logic
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),

        function ($user, $password) {

           // Note: Ensure HASH_PEPPER is set in .env
    $pepperedPassword = hash_hmac('sha256', $password, config('app.pepper', env('HASH_PEPPER')));
    

           $user->update([
                'password' => Hash::make($pepperedPassword)
            ]);
        }
    );

    //  Response
    if ($status === Password::PASSWORD_RESET) {
        return redirect()->route('log')
            ->with('status', 'Password reset successful');
       
    }

    return back()->withErrors(['email' => 'Invalid or expired link']);
}

}



   
    
