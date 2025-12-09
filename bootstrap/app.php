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
    ->withMiddleware(function (Middleware $middleware): void {
        // NOTE: PreventAccessFromCentralDomains removed for local development to
        // avoid 404s when accessing the central registration routes on localhost.
        // If you deploy to production, review tenancy config and re-enable as needed.

        // If you need an alias for tenant middleware, configure it here.
        // $middleware->alias([
        //     'tenant' => \Stancl\Tenancy\Middleware\NeedsTenant::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
