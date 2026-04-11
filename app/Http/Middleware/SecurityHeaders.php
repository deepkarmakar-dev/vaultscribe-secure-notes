<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        //  Basic Security Headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '0'); // modern browsers ignore, but explicitly disable legacy

        //  Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        //  Permissions Policy (extra hardening)
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=()'
        );

        //  Content Security Policy (SAFE VERSION)
   $csp = "
    default-src 'self';

    script-src 'self';

    style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com;

    font-src 'self' data: https://cdnjs.cloudflare.com https://fonts.gstatic.com;

    img-src 'self' data: https://i.pravatar.cc;

    connect-src 'self';

    object-src 'none';
    base-uri 'self';
    form-action 'self';
    frame-ancestors 'self';

    upgrade-insecure-requests;
";
        $response->headers->set('Content-Security-Policy', preg_replace('/\s+/', ' ', trim($csp)));

        //  HSTS (ONLY production + HTTPS)
        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}