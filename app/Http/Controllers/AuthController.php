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


class AuthController extends Controller
{
      public function log()
    {
        return view('log');
    }
    
public function logstore(Request $req)
{
    $attempts = session()->get('login_attempts', 0);

    $req->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

     $pepperedPassword = hash_hmac('sha256', $req->password, config('app.pepper', env('HASH_PEPPER')));
    
  //  After 3 failed attempts → Captcha required
    if ($attempts >= 3) {

        if (!$req->filled('g-recaptcha-response')) {
            return back()->withErrors([
                'email' => 'Captcha required'
            ]);
        }

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('NOCAPTCHA_SECRET'),
                'response' => $req->input('g-recaptcha-response'),
                'remoteip' => $req->ip(),
            ]
        );

        if (!$response->json('success')) {
            return back()->withErrors([
                'email' => 'Captcha failed'
            ]);
        }
    }

    $user = User::where('email', $req->email)->first();

    //  Invalid credentials
    if (!$user || !Hash::check($pepperedPassword, $user->password)) {

        session()->put('login_attempts', $attempts + 1);

        return back()->withErrors([
            'email' => 'Invalid credentials'
        ]);
    }

    //  Email not verified
    if (!$user->email_verified_at) {
        return back()->withErrors([
            'email' => 'Please verify email first'
        ]);
    }

    //  Reset failed attempts
    session()->forget('login_attempts');

  
        //  2FA
        if ($user->google2fa_enabled) {
            session(['2fa_user_id' => $user->id]);
            return redirect()->route('2fa.challenge');
        }
        
    //  Normal Login (No 2FA)
    Auth::login($user);
    $req->session()->regenerate();

    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'login',
        'ip_address' => $req->ip(),
        'user_agent' => $req->userAgent(),
    ]);

    return redirect()->route('dashboard');
}
}