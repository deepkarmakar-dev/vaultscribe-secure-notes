<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorController extends Controller
{
    public function setup()
    {
        // Already enabled? Redirect
        if (auth()->user()->google2fa_enabled) {
            return redirect()->route('dashboard');
        }

        // Secret only generate once
        if (!session()->has('2fa_secret')) {
            session(['2fa_secret' => Google2FA::generateSecretKey()]);
        }

        $secret = session('2fa_secret');

        $QR_Image = Google2FA::getQRCodeInline(
            config('app.name'),
            auth()->user()->email,
            $secret
        );

        return view('2fa-setup', compact('QR_Image', 'secret'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6'
        ]);

        $secret = session('2fa_secret');

        if (!$secret) {
            return redirect()->route('dashboard');
        }

        $valid = Google2FA::verifyKey(
            $secret,
            $request->one_time_password
        );

        if (!$valid) {
            return back()->withErrors([
                'one_time_password' => 'Invalid OTP'
            ]);
        }

        auth()->user()->update([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true
        ]);

        // Clear temporary secret
        session()->forget('2fa_secret');

        // Mark as passed (important)
        session(['2fa_passed' => true]);

        return redirect()->route('dashboard')
            ->with('success', '2FA Enabled Successfully');
    }

    public function challenge()
    {
        if (!auth()->check()) {
            return redirect()->route('log');
        }

        return view('2fa-challenge');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6'
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('log');
        }

        $valid = Google2FA::verifyKey(
            $user->google2fa_secret,
            $request->one_time_password
        );

        if (!$valid) {
            return back()->withErrors([
                'one_time_password' => 'Invalid OTP'
            ]);
        }

        session(['2fa_passed' => true]);

        return redirect()->route('dashboard');
    }

    public function disable()
{
    auth()->user()->update([
        'google2fa_enabled' => false,
        'google2fa_secret' => null,
    ]);

    session()->forget('2fa_passed');

    return redirect()->route('dashboard')
        ->with('success', '2FA Disabled Successfully');
}
}