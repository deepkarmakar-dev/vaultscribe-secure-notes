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
        if (!Auth::check()) {
            return redirect()->route('log');
        }

        // Skip 2FA related routes
        if (
            $request->routeIs('2fa.challenge') ||
            $request->routeIs('2fa.verify') ||
            $request->routeIs('2fa.setup') ||
            $request->routeIs('2fa.enable')
        ) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->google2fa_enabled && !session('2fa_passed')) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}