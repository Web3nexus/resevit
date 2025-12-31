<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConditionalTenancy
{
    /**
     * Handle an incoming request.
     * 
     * Only initialize tenancy if NOT accessing Securegate (Super Admin panel)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Skip tenancy for ALL system assets
        if (
            $request->is('livewire/*') ||
            $request->is('filament/*') ||
            $request->is('vendor/*') ||
            $request->is('@vite/*') ||
            $request->is('build/*') ||
            $request->is('js/*') ||
            $request->is('css/*') ||
            $request->is('assets/*') ||
            $request->is('storage/*') ||
            $request->is('favicon.ico') ||
            $request->is('robots.txt')
        ) {
            // However, Livewire UPDATES (posts) still need tenancy if they aren't from Securegate
            if ($request->is('livewire/update')) {
                $referer = $request->header('referer');
                if ($referer && str_contains($referer, '/securegate')) {
                    return $next($request);
                }
                // Fall through to tenancy initialization for tenant dashboard updates
            } else {
                return $next($request);
            }
        }

        // 2. Skip tenant initialization for Securegate panel and Impersonation
        if ($request->is('securegate') || $request->is('securegate/*') || $request->is('impersonate/*')) {
            return $next($request);
        }

        // 3. Skip tenant initialization ONLY for central domains
        // We removed $request->is('/') because tenant sites also have a root path
        $centralDomains = config('tenancy.central_domains', []);
        $host = $request->getHost();

        if (in_array($host, $centralDomains)) {
            \Illuminate\Support\Facades\Log::info("ConditionalTenancy: Central domain detected, skipping tenancy for: " . $request->path());
            return $next($request);
        }

        \Illuminate\Support\Facades\Log::info("ConditionalTenancy: Initializing tenancy for host: " . $host . " path: " . $request->path());
        // Initialize tenancy for all other domains (tenants)
        return app(\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class)
            ->handle($request, $next);
    }
}
