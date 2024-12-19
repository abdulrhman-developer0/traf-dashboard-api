<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\UpdateUserLastActivity;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckAccountType;
use App\Http\Middleware\TwoFactor;
use App\Http\Middleware\ValidSubscription;
use App\Jobs\BookingRemindersJob;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function (Schedule $schedule) {
        $schedule->job(BookingRemindersJob::class)->everyMinute();
    })
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
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

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'two-factor' => TwoFactor::class,
        ]);
    })

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'valid_subscribtion' => ValidSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
