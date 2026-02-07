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
        $centralDomains = config('tenancy.central_domains', []);
        $host = $request->getHost();
        $previewDomain = config('tenancy.preview_domain');

        // Robust check for central domains (ignoring case and extra spaces)
        $isCentral = $host === $previewDomain || in_array(strtolower($host), array_map('strtolower', $centralDomains));

        // Extra check for Herd local dev domains if not already included
        if (!$isCentral && (str_ends_with($host, '.test') && ($host === 'resevit.test' || $host === 'resevit-backend.test'))) {
            $isCentral = true;
        }

        if ($isCentral) {
            \Illuminate\Support\Facades\Log::info("ConditionalTenancy: Central domain detected [$host], skipping tenancy.");
            return $next($request);
        }

        \Illuminate\Support\Facades\Log::warning("ConditionalTenancy: Non-central domain [$host] - Initializing tenancy. Path: " . $request->path());

        \Illuminate\Support\Facades\Log::info('ConditionalTenancy: Initializing tenancy for host: ' . $host . ' path: ' . $request->path());

        // Initialize tenancy for all other domains (tenants)
        return app(\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class)
            ->handle($request, $next);
    }
}
