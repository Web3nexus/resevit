<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Pipeline;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class FilamentTenantGate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we are on a central domain
        if (in_array($request->getHost(), config('tenancy.central_domains', []))) {

            // Allow Registration explicitly
            if ($this->isRegistrationRoute($request)) {
                return $next($request);
            }

            // For any other Dashboard route on central domain (like /dashboard/login),
            // redirect to the central login page.
            return redirect()->route('login');
        }

        // If NOT on central domain, we MUST be on a tenant domain.
        // Enforce Tenancy as usual, but check if already initialized by global middleware.
        if (function_exists('tenancy') && tenancy()->initialized) {
            return $next($request);
        }

        try {
            return app(Pipeline::class)
                ->send($request)
                ->through([
                    InitializeTenancyByDomain::class,
                    PreventAccessFromCentralDomains::class,
                ])
                ->then($next);
        } catch (\Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException $e) {
            // Tenant not found - redirect to central domain
            return redirect()->to(config('app.url'));
        }
    }

    protected function isRegistrationRoute(Request $request): bool
    {
        // Check content of URL or route name
        return $request->is('dashboard/register') ||
            $request->is('dashboard/register/*') ||
            $request->routeIs('filament.dashboard.auth.register');
    }
}
