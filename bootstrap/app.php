<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\Ensure2FAVerified;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        //  Route Middleware Alias
        $middleware->alias([
            '2fa' => Ensure2FAVerified::class,
        ]);

        //  Global Security Headers (VERY GOOD PRACTICE)
        $middleware->append(SecurityHeaders::class);

        //  Redirect guest (not logged in)
        $middleware->redirectGuestsTo(function () {
            return route('log');
        });

    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();