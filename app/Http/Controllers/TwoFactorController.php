<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Facades\Crypt;

class TwoFactorController extends Controller
{
    public function setup()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('log');
        }

        // Already enabled
        if ($user->google2fa_enabled) {
            return redirect()->route('dashboard');
        }

        // Generate secret once
        if (!session()->has('2fa_secret')) {
            session(['2fa_secret' => Google2FA::generateSecretKey()]);
        }

        $secret = session('2fa_secret');

        $QR_Image = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('2fa-setup', compact('QR_Image', 'secret'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6'
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('log');
        }

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

        //  Encrypt secret before saving
        $user->update([
            'google2fa_secret' => Crypt::encrypt($secret),
            'google2fa_enabled' => true
        ]);

        session()->forget('2fa_secret');
        session(['2fa_passed' => true]);

        return redirect()->route('dashboard')
            ->with('success', '2FA Enabled Successfully');
    }

    public function challenge()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('log');
        }

        if (!$user->google2fa_enabled) {
            return redirect()->route('dashboard');
        }

        // Already passed → avoid loop
        if (session()->has('2fa_passed')) {
            return redirect()->route('dashboard');
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

        if (!$user->google2fa_secret) {
            return redirect()->route('dashboard');
        }

        //  Decrypt secret
        $secret = Crypt::decrypt($user->google2fa_secret);

        $valid = Google2FA::verifyKey(
            $secret,
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
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('log');
        }

        $user->update([
            'google2fa_enabled' => false,
            'google2fa_secret' => null,
        ]);

        session()->forget('2fa_passed');

        return redirect()->route('dashboard')
            ->with('success', '2FA Disabled Successfully');
    }
}