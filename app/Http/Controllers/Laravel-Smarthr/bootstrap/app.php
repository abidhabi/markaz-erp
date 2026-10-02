<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('queue:work --stop-when-empty')
            ->everyMinute();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'localization.parameter' => 'LaravelLang\\Routes\\Middlewares\\LocalizationByParameter',
            'localization.redirect' => 'LaravelLang\\Routes\\Middlewares\\LocalizationByParameterWithRedirect',
            'localization.header' => 'LaravelLang\\Routes\\Middlewares\\LocalizationByHeader',
            'localization.cookie' => 'LaravelLang\\Routes\\Middlewares\\LocalizationByCookie',
            'localization.session' => 'LaravelLang\\Routes\\Middlewares\\LocalizationBySession',
            'localization.model' => 'LaravelLang\\Routes\\Middlewares\\LocalizationByModel',
            'role' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
