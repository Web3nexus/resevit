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
        // Skip tenant initialization for Securegate panel
        if ($request->is('securegate') || $request->is('securegate/*')) {
            return $next($request);
        }

        // For Livewire requests, check the referer to determine context
        if ($request->is('livewire/*')) {
            $referer = $request->header('referer');
            if ($referer && str_contains($referer, '/securegate')) {
                return $next($request);
            }
        }

        // Skip tenant initialization for central domains or ROOT landing page
        if ($request->is('/') || in_array($request->getHost(), config('tenancy.central_domains', []))) {
            \Illuminate\Support\Facades\Log::info("ConditionalTenancy: Global route detected, skipping tenancy for: " . $request->path());
            return $next($request);
        }

        \Illuminate\Support\Facades\Log::info("ConditionalTenancy: Initializing tenancy for: " . $request->path());
        // Initialize tenancy for all other routes
        return app(\Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class)
            ->handle($request, $next);
    }
}
