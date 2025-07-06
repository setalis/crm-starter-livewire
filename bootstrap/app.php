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
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdminAccess::class,
            'manager' => \App\Http\Middleware\CheckManagerAccess::class,
        ]);
        
        // Добавляем middleware для обновления разрешений во все веб-роуты
        $middleware->web(append: [
            \App\Http\Middleware\RefreshUserPermissions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
