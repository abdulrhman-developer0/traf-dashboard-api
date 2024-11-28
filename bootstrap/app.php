<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\UpdateUserLastActivity;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckAccountType;
use App\Http\Middleware\TwoFactor;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            UpdateUserLastActivity::class,
        ]);

        $middleware->alias([
            'admin'     => AdminMiddleware::class,
            'account'   => CheckAccountType::class

        ]);
        
    })

    ->withMiddleware(function(Middleware $middleware) {

        $middleware->alias([
            'two-factor' =>TwoFactor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
