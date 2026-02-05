<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__ . '/../app/Console/Commands',
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // NOTE: PreventAccessFromCentralDomains removed for local development to
        // avoid 404s when accessing the central registration routes on localhost.
        // If you deploy to production, review tenancy config and re-enable as needed.
    
        // Initialize tenancy on web routes, but skip for Securegate (Super Admin panel)
        $middleware->web(prepend: [
            \App\Http\Middleware\ConditionalTenancy::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetUserLocalization::class,
            \App\Http\Middleware\TrackReferralClicks::class,
            \App\Http\Middleware\SecureHeaders::class,
        ]);

        $middleware->alias([
            'feature' => \App\Http\Middleware\CheckFeatureAccess::class,
            'platform.protect' => \App\Http\Middleware\PreventPlatformAccessFromCustomDomains::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/social/*',
            'stripe/webhook',
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Send reservation reminders hourly
        $schedule->job(\App\Jobs\SendReservationReminders::class)->hourly();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            return response()->view('errors.tenant-not-found', [], 404);
        });
    })->create();
