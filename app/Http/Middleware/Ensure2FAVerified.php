<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Ensure2FAVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        // ❌ Not logged in
        if (!Auth::check()) {
            return redirect()->route('log');
        }

        // ✅ Skip 2FA routes (important to avoid loop)
        if ($request->routeIs([
            '2fa.challenge',
            '2fa.verify',
            '2fa.setup',
            '2fa.enable'
        ])) {
            return $next($request);
        }

        $user = Auth::user();

        // 🔐 If 2FA enabled but not verified
        if ($user->google2fa_enabled && !session()->has('2fa_passed')) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}