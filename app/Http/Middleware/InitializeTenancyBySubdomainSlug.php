<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Resolvers\PathTenantResolver;
use Stancl\Tenancy\Tenancy;
use App\Models\Tenant;

class InitializeTenancyBySubdomainSlug extends InitializeTenancyBySubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 1. Identify the subdomain
        $subdomain = $this->makeSubdomain($request->getHost());

        // 2. If it's a central domain/not a subdomain, pass through (or let parent handle it if it throws)
        // actually makeSubdomain returns exception if fails.
        if ($subdomain instanceof \Exception) {
            throw $subdomain;
        }

        // 3. Find tenant by SLUG
        $tenant = Tenant::where('slug', $subdomain)->first();

        if (!$tenant) {
            throw new \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException($request->getHost());
        }

        // 4. Initialize tenancy
        $this->tenancy->initialize($tenant);

        return $next($request);
    }
}
