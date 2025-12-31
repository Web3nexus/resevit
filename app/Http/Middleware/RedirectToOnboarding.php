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
        if (tenant() && !tenant('onboarding_completed')) {
            $onboardingUrl = route('filament.dashboard.pages.onboarding');

            // Avoid redirect loop
            if ($request->url() !== $onboardingUrl && !str_contains($request->url(), 'livewire')) {
                return redirect($onboardingUrl);
            }
        }

        return $next($request);
    }
}
