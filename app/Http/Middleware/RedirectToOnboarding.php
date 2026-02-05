<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        // Only redirect if both indicators show onboarding is NOT complete
        $isComplete = $tenant && ($tenant->onboarding_status === 'active' || $tenant->onboarding_completed);

        if ($tenant && !$isComplete) {
            $onboardingRoute = 'filament.dashboard.pages.onboarding';
            $onboardingUrl = route($onboardingRoute);

            // Avoid redirect loop for the onboarding page itself and livewire/internal requests
            if (
                !$request->routeIs($onboardingRoute) &&
                !$request->routeIs('filament.dashboard.auth.*') &&
                !str_contains($request->url(), 'livewire') &&
                !$request->ajax()
            ) {

                \Illuminate\Support\Facades\Log::info('Redirecting to onboarding', [
                    'tenant_id' => $tenant->id,
                    'status' => $tenant->onboarding_status,
                    'url' => $request->url(),
                    'route' => $request->route()?->getName(),
                ]);

                return redirect($onboardingUrl);
            }
        }

        return $next($request);
    }
}
