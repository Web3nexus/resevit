<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Helpers\DomainHelper;

class DashboardRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // 1. Try Customer guard
        if (Auth::guard('customer')->check()) {
            return redirect('/customer');
        }

        // 2. Try Investor guard
        if (Auth::guard('investor')->check()) {
            return redirect('/invest');
        }

        // 3. Try Landlord (Web) guard
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Case A: Super Admin
            if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
                return redirect('/securegate');
            }

            // Case B: Business Owner
            $tenants = $user->tenants;

            if ($tenants->isEmpty()) {
                // No tenant yet, send to registration to create one
                return redirect()->route('filament.dashboard.auth.register');
            }

            // If they have exactly one tenant, redirect to it
            if ($tenants->count() === 1) {
                return $this->redirectToTenant($tenants->first());
            }

            // Multiple tenants? Send to selection (if route exists) or first tenant
            if (Route::has('central.tenant-selection')) {
                return redirect()->route('central.tenant-selection');
            }

            return $this->redirectToTenant($tenants->first());
        }

        // Fallback: If somehow reached while not logged in, go to home
        return redirect()->route('home');
    }

    protected function redirectToTenant($tenant)
    {
        $protocol = request()->secure() ? 'https://' : 'http://';

        $hasWhitelabelFeature = has_feature('whitelabel', $tenant);
        $isWhitelabelActive = $tenant->whitelabel_active;
        $dashboardDomain = $tenant->dashboard_custom_domain;

        if ($hasWhitelabelFeature && $isWhitelabelActive && $dashboardDomain) {
            $domain = $dashboardDomain;
        } else {
            $domain = DomainHelper::getPlatformSubdomain($tenant->slug);
        }

        if (!$domain) {
            return redirect()->route('home');
        }

        return redirect($protocol . $domain . '/dashboard');
    }
}
