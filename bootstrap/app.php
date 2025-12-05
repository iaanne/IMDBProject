<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'db.connection' => \App\Http\Middleware\SetDatabaseConnection::class,
        ]);

        // Apply database connection middleware to API routes
        $middleware->api(prepend: [
            \App\Http\Middleware\SetDatabaseConnection::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();