<?php

use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Middleware\EnsureIsUser;
use App\Http\Middleware\EnsureIsSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.any' => EnsureAuthenticated::class,
            'auth.user' => EnsureIsUser::class,
            'auth.superadmin' => EnsureIsSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
