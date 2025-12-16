<?php

namespace App\Http\Middleware;

use App\Services\TimezoneService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantTimezone
{
    protected TimezoneService $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only set timezone if tenancy is initialized
        if (tenancy()->initialized) {
            $timezone = $this->timezoneService->getCurrentTenantTimezone();

            // Set the application timezone
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
        }

        return $next($request);
    }
}
