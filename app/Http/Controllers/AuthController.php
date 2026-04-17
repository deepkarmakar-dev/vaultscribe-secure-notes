<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function log()
    {
        return view('log');
    }
    
    public function logstore(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $attempts = session()->get('login_attempts', 0);

        // CAPTCHA
        if ($attempts >= 3) {

            if ($attempts == 3) {
                ActivityLog::create([
                    'user_id' => null,
                    'action' => 'captcha_triggered',
                    'ip_address' => $req->ip(),
                    'user_agent' => $req->userAgent(),
                ]);
            }

            if (!$req->filled('g-recaptcha-response')) {
                return back()->withErrors(['email' => 'Captcha required']);
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
                return back()->withErrors(['email' => 'Captcha failed']);
            }
        }

        // 🔐 Pepper (same as register)
        $pepperedPassword = hash_hmac(
            'sha256',
            $req->password,
            config('app.pepper')
        );

        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($pepperedPassword, $user->password)) {

            $attempts = session()->increment('login_attempts');

            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => 'login_failed',
                'ip_address' => $req->ip(),
                'user_agent' => $req->userAgent(),
            ]);

            if ($attempts >= 5) {
                ActivityLog::create([
                    'user_id' => $user?->id,
                    'action' => 'suspicious_login_attempt',
                    'ip_address' => $req->ip(),
                    'user_agent' => $req->userAgent(),
                ]);
            }

            sleep(1);

            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        if (!$user->email_verified_at) {
            return back()->withErrors(['email' => 'Please verify email first']);
        }

        session()->forget('login_attempts');

        if ($user->google2fa_enabled) {
            session(['2fa_user_id' => $user->id]);
            return redirect()->route('2fa.challenge');
        }

        Auth::login($user);
        $req->session()->regenerate();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login_success',
            'ip_address' => $req->ip(),
            'user_agent' => $req->userAgent(),
        ]);

        return redirect()->route('dashboard');
    }
}