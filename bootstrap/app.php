<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\EventServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register alias middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        // Update exchange rates every 5 minutes
        $schedule->command('exchange:update')->everyFiveMinutes();
        
        // Optional: More frequent updates during business hours (9 AM to 5 PM, weekdays)
        $schedule->command('exchange:update')
            ->everyMinute()
            ->between('9:00', '17:00')
            ->weekdays();
            
        // Clean old exchange rate records (keep only last 7 days)
        $schedule->command('exchange:cleanup')->daily();
    })
    ->create();