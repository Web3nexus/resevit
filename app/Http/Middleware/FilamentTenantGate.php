<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Pipeline;
use App\Http\Middleware\InitializeTenancyBySubdomainSlug;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class FilamentTenantGate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // 1. Check if we are on a central domain
        if (\App\Helpers\DomainHelper::isCentral($host)) {
            // Allow Registration explicitly
            if ($this->isRegistrationRoute($request)) {
                return $next($request);
            }

            // For any other Dashboard route on central domain (like /dashboard/login),
            // redirect to the central login page.
            return redirect()->route('login');
        }

        // 2. If it's a CUSTOM domain, we prevent Dashboard access and redirect to platform subdomain
        if (\App\Helpers\DomainHelper::isCustomDomain($host)) {
            // We need to identify the tenant to know where to redirect
            try {
                // Initialize tenancy temporarily to get the tenant object if not already done
                if (!function_exists('tenancy') || !tenancy()->initialized) {
                    app(InitializeTenancyByDomain::class)->handle($request, function ($req) {
                        return response('initialized');
                    });
                }

                $tenant = tenant();
                if ($tenant) {
                    $hasWhitelabelFeature = has_feature('whitelabel', $tenant);
                    $isWhitelabelActive = $tenant->whitelabel_active;
                    $dashboardDomain = $tenant->dashboard_custom_domain;
                    $websiteDomain = $tenant->website_custom_domain;

                    // 1. If whitelabeling is ON and we are on the allocated Dashboard domain, allow access.
                    if ($hasWhitelabelFeature && $isWhitelabelActive && $dashboardDomain && $host === $dashboardDomain) {
                        return $next($request);
                    }

                    // 2. Identify the target platform/dashboard host for redirection
                    $targetHost = ($hasWhitelabelFeature && $isWhitelabelActive && $dashboardDomain)
                        ? $dashboardDomain
                        : \App\Helpers\DomainHelper::getPlatformSubdomain($tenant->slug);

                    // If we are already on the target host (e.g. platform subdomain), we shouldn't be in this block,
                    // but safety first.
                    if ($host !== $targetHost) {
                        $path = $request->getPathInfo();
                        $query = $request->getQueryString();
                        $protocol = $request->secure() ? 'https://' : 'http://';
                        $url = $protocol . $targetHost . $path . ($query ? '?' . $query : '');

                        return redirect()->to($url);
                    }
                }
            } catch (\Exception $e) {
                // If we can't identify tenant, just let it fall through to identification below
            }
        }

        // 3. If on platform subdomain or fallback, enforce Tenancy
        if (function_exists('tenancy') && tenancy()->initialized) {
            return $next($request);
        }

        try {
            return app(Pipeline::class)
                ->send($request)
                ->through([
                    InitializeTenancyBySubdomainSlug::class,
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
