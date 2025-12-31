<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Branch;
use Symfony\Component\HttpFoundation\Response;

class ScopePermissionsByBranch
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ensure a branch is selected if we are in a tenant context
        if (function_exists('tenant') && tenant()) {
            if (!Session::exists('current_branch_id')) {
                $firstBranch = Branch::where('is_active', true)->first();
                if ($firstBranch) {
                    Session::put('current_branch_id', $firstBranch->id);
                }
            }
        }

        // 2. Set Team ID for Spatie Permissions scoping
        // teams is now enabled globally in config/permission.php
        if (Session::has('current_branch_id')) {
            setPermissionsTeamId(Session::get('current_branch_id'));
        }

        return $next($request);
    }
}
