<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware alias tanımlaması
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // Tarayıcı diline göre otomatik locale ayarlama
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // CSRF korumasından muaf tutulacak URL'ler
        $middleware->validateCsrfTokens(except: [
            'checkout/callback', // İyzico 3D Secure callback
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
