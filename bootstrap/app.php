<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\App;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/routes/web.php',
        api: __DIR__ . '/routes/api.php',
        commands: __DIR__ . '/routes/console.php',
        health: __DIR__ . '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'adminAuth' => \App\Http\Middleware\AdminAuth::class,
            'userAuth' => \App\Http\Middleware\UserAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
