<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\DomainHelper;

class PreventPlatformAccessFromCustomDomains
{
    /**
     * Handle an incoming request.
     *
     * Prevent access to platform-wide pages (Pricing, Features, etc.) 
     * when the request is coming from a custom domain.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // If it's a custom domain, we only allow access to the website home and website-specific routes
        // Platform routes (like /pricing, /features) should redirect to the home page of the custom domain or 404
        if (DomainHelper::isCustomDomain($host)) {
            $tenant = tenant();

            // Strict Feature Gate: Only Pro/Enterprise (who have 'custom_domain' feature) can use custom domains.
            if ($tenant && !has_feature('custom_domain', $tenant)) {
                $platformHost = DomainHelper::getPlatformSubdomain($tenant->slug);
                $protocol = $request->secure() ? 'https://' : 'http://';
                return redirect()->to($protocol . $platformHost . '/' . $request->path());
            }

            $hasWhitelabelFeature = $tenant && has_feature('whitelabel', $tenant);
            $isWhitelabelActive = $tenant && $tenant->whitelabel_active;
            $dashboardDomain = $tenant ? $tenant->dashboard_custom_domain : null;

            // 1. If this is the dashboard custom domain, allow everything (it's whitelabeled platform)
            if ($hasWhitelabelFeature && $isWhitelabelActive && $dashboardDomain && $host === $dashboardDomain) {
                return $next($request);
            }

            // 2. Otherwise, check if the current route is allowed on the business website domain
            $path = $request->path();
            $allowedPaths = ['/', 'menu', 'checkout', 'order/*', 'contact/submit', 'newsletter/subscribe', 'webhooks/social/*', 'impersonate/*'];

            $isAllowed = false;
            foreach ($allowedPaths as $allowed) {
                if ($request->is($allowed)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                // If it's a platform route accessed via custom domain, redirect to home or 404
                // Aborting with 404 is often cleaner to signal the page doesn't exist on this domain
                abort(404);
            }
        }

        return $next($request);
    }
}
