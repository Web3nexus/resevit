<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        $tenant = tenant();

        if (!$tenant) {
            return $next($request);
        }

        $featureManager = app(\App\Services\FeatureManager::class);

        if (!$featureManager->hasFeature($tenant, $featureKey)) {
            abort(403, "Your current plan does not include the '{$featureKey}' feature. Please upgrade your plan.");
        }

        if ($featureManager->isTrialExpired($tenant)) {
            abort(403, "Your trial has expired. Please upgrade to a paid plan to continue using this feature.");
        }

        return $next($request);
    }
}
