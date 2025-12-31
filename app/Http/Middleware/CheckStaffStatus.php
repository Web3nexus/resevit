<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStaffStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only check if we're in a tenant context and user is authenticated
        if ($user && function_exists('tenant') && tenant()) {
            // Check if user has a staff record
            $staff = \App\Models\Staff::where('user_id', $user->id)->first();

            if ($staff && in_array($staff->status, ['suspended', 'terminated'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = $staff->status === 'terminated'
                    ? 'Your employment has been terminated. Please contact your administrator.'
                    : 'Your account has been suspended. Please contact your administrator.';

                return redirect()->route('login')->with('error', $message);
            }
        }

        return $next($request);
    }
}
