<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OtpController extends Controller
{
    public function otp()
    {
        if (!session('pending_user')) {
            return redirect()->route('log')
                ->withErrors(['error' => 'Please register first']);
        }

        return view('otp');
    }

    public function verifyOtp(Request $req)
    {
        $req->validate([
            'otp' => 'required|digits:6',
        ]);

        $attempts = session()->get('otp_attempts', 0);

        if ($attempts >= 5) {
            session()->flush();

            return redirect()->route('log')
                ->withErrors(['otp' => 'Too many attempts']);
        }

        if (!session('otp_expires_at') || now()->greaterThan(session('otp_expires_at'))) {
            session()->forget(['registration_otp', 'otp_attempts', 'otp_expires_at']);

            return back()->withErrors(['otp' => 'OTP expired']);
        }

        if (!Hash::check($req->otp, session('registration_otp'))) {
            session()->increment('otp_attempts');

            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        $userData = session('pending_user');

        if (!$userData) {
            return redirect()->route('log')
                ->withErrors(['error' => 'Session expired']);
        }

        if (User::where('email', $userData['email'])->exists()) {
            session()->flush();

            return redirect()->route('log')
                ->withErrors(['email' => 'User already exists']);
        }

        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $userData['password'], // already peppered+hashed
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $req->session()->regenerate();

        session()->forget([
            'pending_user',
            'registration_otp',
            'otp_attempts',
            'otp_expires_at'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Registration successful');
    }
}