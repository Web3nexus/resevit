<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCustomerRole
{
    /**
     * Handle an incoming request.
     * Ensures the authenticated user from the 'customer' guard has the 'customer' role.
     */
    public function handle(Request $request, Closure $next)
    {
        $guard = 'customer';

        // Prefer the dedicated customer guard, but fall back to the default authenticated user.
        $user = Auth::guard($guard)->user() ?: Auth::user();

        if (! $user) {
            return redirect()->guest('/customer/login');
        }

        $hasRole = false;

        if (method_exists($user, 'hasRole')) {
            $hasRole = $user->hasRole('customer');
        } elseif (property_exists($user, 'role')) {
            $hasRole = $user->role === 'customer';
        } elseif (isset($user->roles) && is_array($user->roles)) {
            $hasRole = in_array('customer', $user->roles, true);
        }

        if (! $hasRole) {
            Auth::guard($guard)->logout();

            return redirect()->guest('/customer/login');
        }

        return $next($request);
    }
}
