<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class DomainHelper
{
    /**
     * Check if the given host is a central domain.
     */
    public static function isCentral(string $host): bool
    {
        $centralDomains = config('tenancy.central_domains', []);

        // Normalize host by removing www. for the check
        $hostWithoutWww = preg_replace('/^www\./', '', $host);

        return in_array($host, $centralDomains) || in_array($hostWithoutWww, $centralDomains);
    }

    /**
     * Check if the given host is a platform subdomain (e.g., manager3.resevit.test).
     */
    public static function isPlatformSubdomain(string $host): bool
    {
        if (self::isCentral($host)) {
            return false;
        }

        $centralDomains = config('tenancy.central_domains', []);

        foreach ($centralDomains as $central) {
            if (Str::endsWith($host, '.' . $central)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the given host is a custom domain (not central and not a platform subdomain).
     */
    public static function isCustomDomain(string $host): bool
    {
        return !self::isCentral($host) && !self::isPlatformSubdomain($host);
    }

    /**
     * Get the platform subdomain for a tenant.
     * 
     * @param string $slug The tenant slug
     * @return string
     */
    public static function getPlatformSubdomain(string $slug): string
    {
        $currentHost = request()->getHost();

        // 1. If we are currently on a central domain (like resevit.test or localhost),
        // we use it as the base for the subdomain. This is the most reliable way
        // to handle mixed local/prod environments.
        if (self::isCentral($currentHost)) {
            return "{$slug}.{$currentHost}";
        }

        // 2. If we are on a custom domain, we derive the platform base from app.url
        $appUrl = config('app.url');
        $platformHost = parse_url($appUrl, PHP_URL_HOST);

        if (!$platformHost || filter_var($platformHost, FILTER_VALIDATE_IP) || $platformHost === 'localhost') {
            // Fallback to searching central domains if app.url is not helpful
            $centralDomains = config('tenancy.central_domains', []);
            foreach ($centralDomains as $domain) {
                if (!filter_var($domain, FILTER_VALIDATE_IP) && $domain !== 'localhost') {
                    return "{$slug}.{$domain}";
                }
            }

            // Final fallback
            return "{$slug}." . ($centralDomains[0] ?? parse_url(config('app.url'), PHP_URL_HOST));
        }

        return "{$slug}.{$platformHost}";
    }
}
