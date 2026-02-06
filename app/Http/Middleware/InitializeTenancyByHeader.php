<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\IdentificationMiddleware;
use Stancl\Tenancy\Tenancy;

class InitializeTenancyByHeader extends IdentificationMiddleware
{
    /** @var Tenancy */
    protected $tenancy;

    public function __construct(Tenancy $tenancy, \Stancl\Tenancy\Resolvers\PathTenantResolver $resolver)
    {
        $this->tenancy = $tenancy;
        // We probably don't need the resolver if we just find by ID,
        // but IdentificationMiddleware usually expects one.
        // However, we'll implement handle directly.
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 1. Check if tenancy is already initialized
        if ($this->tenancy->initialized) {
            return $next($request);
        }

        // 2. Look for X-Tenant header
        $tenantId = $request->header('X-Tenant');

        if ($tenantId) {
            // 3. Initialize tenancy manually
            try {
                $this->tenancy->initialize($tenantId);
            } catch (\Exception $e) {
                // Tenant not found or other error.
                // We can let it pass, but usually we want to 404 if tenant context is required.
                abort(404, 'Tenant not found');
            }
        }

        return $next($request);
    }
}
