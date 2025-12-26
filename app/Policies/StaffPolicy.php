<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class StaffPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;

        return has_feature('staff');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, Staff $staff): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasAnyRole(['business_owner', 'manager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;
        // Temporarily allow all authenticated users
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, Staff $staff): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasAnyRole(['business_owner', 'manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Staff $staff): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('business_owner');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Authenticatable $user, Staff $staff): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('business_owner');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Authenticatable $user, Staff $staff): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('business_owner');
    }
}
