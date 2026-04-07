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

        // Paddle webhook CSRF muafiyeti (webhook route'u api.php'de olacaksa buraya gerek yok)
        $middleware->validateCsrfTokens(except: [
            'paddle/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
