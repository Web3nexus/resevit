<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Tenant;

class CentralLoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.central-login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // 1. Try Business Owner (Web Guard)
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            \Illuminate\Support\Facades\Log::info('CENTRAL LOGIN: Web guard success for ' . $credentials['email']);
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            $tenants = $user->tenants;
            \Illuminate\Support\Facades\Log::info('CENTRAL LOGIN: User found with ' . $tenants->count() . ' tenants.');

            if ($tenants->isEmpty()) {
                // If Business Owner has no tenant, send to registration to create one
                return redirect()->route('filament.dashboard.auth.register');
            }

            if ($tenants->count() === 1) {
                return $this->redirectToTenant($tenants->first());
            }

            return redirect()->route('central.tenant-selection');
        }

        // 2. Try Customer
        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect('/customer');
        }

        // 3. Try Investor
        if (Auth::guard('investor')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect('/invest');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    protected function redirectToTenant(Tenant $tenant)
    {
        $protocol = request()->secure() ? 'https://' : 'http://';

        // Assuming domains relationship or 'domain' column on tenant
        // Stancl Tenancy usually puts domains in a separate table
        $domain = $tenant->domains->first()?->domain;

        if (!$domain) {
            \Illuminate\Support\Facades\Log::error('CENTRAL LOGIN: No domain found for tenant ' . $tenant->id);
            // Fallback if no domain found implementation
            return redirect()->back()->withErrors(['email' => 'No domain found for your account.']);
        }

        $url = $protocol . $domain . '/dashboard';
        \Illuminate\Support\Facades\Log::info('CENTRAL LOGIN: Redirecting to ' . $url);

        return redirect($url);
    }
}
