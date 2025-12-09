<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureInvestorRole
{
    /**
     * Handle an incoming request.
     * Ensures the authenticated user from the 'investor' guard has the 'investor' role.
     */
    public function handle(Request $request, Closure $next)
    {
        $guard = 'investor';

        // Prefer the dedicated investor guard, but fall back to the default authenticated user.
        $user = Auth::guard($guard)->user() ?: Auth::user();

        // If there's no authenticated user at all, redirect to investor login
        if (! $user) {
            return redirect()->guest('/invest/login');
        }

        // If the user model uses Spatie roles, prefer hasRole check; otherwise, try a simple role property
        $hasRole = false;

        if (method_exists($user, 'hasRole')) {
            $hasRole = $user->hasRole('investor');
        } elseif (property_exists($user, 'role')) {
            $hasRole = $user->role === 'investor';
        } elseif (isset($user->roles) && is_array($user->roles)) {
            $hasRole = in_array('investor', $user->roles, true);
        }

        if (! $hasRole) {
            Auth::guard($guard)->logout();

            return redirect()->guest('/invest/login');
        }

        return $next($request);
    }
}
