<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;

        return has_feature('pos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin)
            return true;
        // Tenant scope is handled by Global Scope / Database Connection
        // Additional check: User must be part of this tenant context
        return $user instanceof User && $user->hasRole(['Business Owner', 'Staff']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner', 'Staff']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole(['Business Owner', 'Staff']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('Business Owner'); // Only Owner can delete
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('Business Owner');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('Business Owner');
    }
}
