<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantBasePolicy
{
    use HandlesAuthorization;

    /**
     * Business Owners and Staff can view anything in their tenant.
     */
    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner', 'Staff']);
    }

    public function view(Authenticatable $user, $model): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner', 'Staff']);
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner', 'Staff']);
    }

    public function update(Authenticatable $user, $model): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner', 'Staff']);
    }

    public function delete(Authenticatable $user, $model): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner']);
    }

    public function deleteAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner']);
    }

    public function forceDelete(Authenticatable $user, $model): bool
    {
        return false; // Typically restricted
    }

    public function restore(Authenticatable $user, $model): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner']);
    }
}
