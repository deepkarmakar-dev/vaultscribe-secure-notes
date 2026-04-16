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



     //  OTP PAGE
class OtpController extends Controller
{

    public function otp()
    {
        if (!session('otp_email')) {
            return redirect()->route('log');
        }

        return view('otp');
    }

    public function verifyOtp(Request $req)
    {
    $req->validate([
        'otp' => 'required|digits:6',
    ]);

    $email = session('otp_email');
    if (!$email) {
        return redirect()->route('log');
    }

    // IMPROVEMENT: Track OTP attempts using session
    $attempts = session()->get('otp_attempts', 0);

    if ($attempts >= 5) {
        session()->forget(['otp_email', 'otp_attempts']);

        return redirect()->route('log')
            ->withErrors(['otp' => 'Too many OTP attempts. Please login again.']);
    }

    $user = User::where('email', $email)->first();

    if (!$user ||!$user->otp ||now()->greaterThan($user->otp_expires_at) ||!Hash::check(trim($req->otp), $user->otp)) {
        // IMPROVEMENT: Increase OTP attempt count
        session()->put('otp_attempts', $attempts + 1);

        return back()->withErrors([
            'otp' => 'Invalid or expired OTP'
        ]);
    }

    // OTP verified successfully
    $user->update([
        'otp' => null,
        'otp_expires_at' => null,
        'email_verified_at' => now(),
    ]);

    // IMPROVEMENT: Reset OTP session data after success
    session()->forget(['otp_email', 'otp_attempts']);

    return redirect()->route('log');
}
}