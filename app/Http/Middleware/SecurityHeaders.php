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

       
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=(), usb=()'
        );

        
        $csp = "
            default-src 'self';
            script-src 'self' 'unsafe-inline' 'unsafe-eval' 
                https://www.google.com/recaptcha/ 
                https://www.gstatic.com/recaptcha/;
            style-src 'self' 'unsafe-inline' 
                https://cdnjs.cloudflare.com 
                https://fonts.googleapis.com;
            font-src 'self' data: 
                https://cdnjs.cloudflare.com 
                https://fonts.gstatic.com;
            img-src 'self' data: 
                https://i.pravatar.cc 
                https://www.google.com 
                https://www.gstatic.com;
            connect-src 'self' 
                https://www.google.com/recaptcha/ 
                https://www.gstatic.com;
            frame-src 'self' 
                https://www.google.com/recaptcha/ 
                https://recaptcha.google.com/recaptcha/;
            object-src 'none';
            base-uri 'self';
            form-action 'self';
            frame-ancestors 'self';
            upgrade-insecure-requests;
        ";

        $response->headers->set('Content-Security-Policy', preg_replace('/\s+/', ' ', trim($csp)));

     
        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

   
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        
      
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');

        return $response;
    }
}