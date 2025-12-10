<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Handle Business Owner redirection
        if ($user->hasRole('business-owner')) {
            $tenant = $user->tenants()->first();

            if ($tenant) {
                $domain = $tenant->domains->first()->domain;
                return redirect()->to("//{$domain}/dashboard");
            }
        }

        // Handle other roles
        $redirectPath = match (true) {
            $user->hasRole('super-admin') => route('filament.securegate.pages.dashboard'),
            $user->hasRole('investor') => route('filament.invest.pages.dashboard'),
            $user->hasRole('customer') => route('filament.customer.pages.dashboard'),
            default => '/dashboard', // Fallback
        };

        return redirect()->intended($redirectPath);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
