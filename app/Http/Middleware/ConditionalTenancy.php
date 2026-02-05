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
        // 1. Skip tenancy for STATIC system assets
        if (
            $request->is('filament/assets/*') ||
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
            return $next($request);
        }

        // Livewire assets (js) are central, but upload/update/preview are tenant-aware
        if ($request->is('livewire/*')) {
            \Illuminate\Support\Facades\Log::info('ConditionalTenancy: Livewire request detected: ' . $request->path());

            if ($request->is('livewire/livewire.js') || $request->is('livewire/livewire.min.js.map')) {
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

        // DEBUG LOGGING
        \Illuminate\Support\Facades\Log::info("ConditionalTenancy Debug: Host [$host], Central Domains: " . implode(', ', $centralDomains));

        if (in_array($host, $centralDomains)) {
            \Illuminate\Support\Facades\Log::info('ConditionalTenancy: Central domain detected, skipping tenancy for: ' . $request->path());

            return $next($request);
        }

        \Illuminate\Support\Facades\Log::info('ConditionalTenancy: Initializing tenancy for host: ' . $host . ' path: ' . $request->path());

        // Initialize tenancy for all other domains (tenants)
        return app(\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class)
            ->handle($request, $next);
    }
}
