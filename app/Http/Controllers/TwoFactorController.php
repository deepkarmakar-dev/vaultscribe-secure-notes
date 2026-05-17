<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TwoFactorController extends Controller
{
    //      2FA Setup Page
    public function setup()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->google2fa_enabled) {
            return redirect()->route('dashboard');
        }

        // Generate secret if not exists
        if (!session()->has('2fa_secret')) {
            session(['2fa_secret' => Google2FA::generateSecretKey()]);
        }

        // Define secret properly
        $secret = session('2fa_secret');

        // Generate QR
        $QR_Image = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        //  Pass both QR and secret
        return view('2fa-setup', compact('QR_Image', 'secret'));
    }

    //  Enable 2FA
    public function enable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6'
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $secret = session('2fa_secret');

        if (!$secret) {
            return redirect()->route('2fa.setup');
        }

        // Verify OTP
        if (!Google2FA::verifyKey($secret, $request->one_time_password, 2)) {
            return back()->withErrors([
                'one_time_password' => 'Invalid OTP'
            ]);
        }

        // Save in DB (encrypted)
        $user->update([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true
        ]);

        // Cleanup + mark verified
        session()->forget('2fa_secret');
        session(['2fa_passed' => true]);

        return redirect()->route('dashboard')->with('success', '2FA Enabled');
    }

    //  Challenge Page
    public function challenge()
    {
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('2fa-challenge');
    }

    //  Verify OTP (Login Step)
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6'
        ]);

        $userId = session('2fa_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user || !$user->google2fa_secret) {
            return redirect()->route('login');
        }

        $secret = $user->google2fa_secret;

        // Verify OTP
        if (!Google2FA::verifyKey($secret, $request->one_time_password, 2)) {
            return back()->withErrors([
                'one_time_password' => 'Invalid OTP'
            ]);
        }

        //  Final Login
        Auth::login($user);

        //  IMPORTANT: Only regenerate (NO invalidate)
        $request->session()->regenerate();

        // Mark 2FA passed
        session(['2fa_passed' => true]);

        // Cleanup
        session()->forget(['2fa_user_id']);

        return redirect()->route('dashboard');
    }

    //  Disable 2FA
    public function disable(Request $request)
{
    // 1. Password validation check
    $request->validate([
        'password' => 'required|current_password', 
    ]);

    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // 2. 2FA disable karna
    $user->update([
        'google2fa_enabled' => false,
        'google2fa_secret' => null,
    ]);

    session()->forget('2fa_passed');

    return redirect()->route('dashboard')->with('success', '2FA Disabled successfully');
}
}