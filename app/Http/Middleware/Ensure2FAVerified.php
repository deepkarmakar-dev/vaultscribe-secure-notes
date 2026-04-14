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
        //  Allow only valid states
        if (!Auth::check() && !session()->has('2fa_user_id')) {
            return redirect()->route('log');
        }

        //  Skip 2FA routes
        if ($request->routeIs([
            '2fa.challenge',
            '2fa.verify',
            '2fa.setup',
            '2fa.enable',
            'logout'
        ])) {
            return $next($request);
        }

        $user = Auth::user();

        //  If logged in but 2FA not passed
        if ($user && $user->google2fa_enabled && !session()->get('2fa_passed')) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}