<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow the default authenticated user to be checked
        $user = Auth::user();

        if (! $user) {
            return redirect()->guest('/securegate/login');
        }

        // Prefer Spatie hasRole if available, otherwise check a role property
        $isSuper = false;
        if (method_exists($user, 'hasRole')) {
            $isSuper = $user->hasRole('super-admin') || $user->hasRole('admin');
        } elseif (property_exists($user, 'role')) {
            $isSuper = $user->role === 'super-admin' || $user->role === 'admin';
        }

        if (! $isSuper) {
            Auth::logout();
            return redirect()->guest('/securegate/login');
        }

        return $next($request);
    }
}
