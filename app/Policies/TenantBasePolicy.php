<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantBasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    /**
     * Determine whether the user can view any models.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function viewAny($user): bool
    {
        if ($user instanceof \App\Models\Admin) {
            return true;
        }
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function view($user, $model): bool
    {
        if ($user instanceof \App\Models\Admin) {
            return true;
        }
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function create($user): bool
    {
        if ($user instanceof \App\Models\Admin) {
            return true;
        }
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function update($user, $model): bool
    {
        if ($user instanceof \App\Models\Admin) {
            return true;
        }
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function delete($user, $model): bool
    {
        if ($user instanceof \App\Models\Admin) {
            return true;
        }
        return $user->hasRole('Business Owner');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function restore($user, $model): bool
    {
        if ($user instanceof \App\Models\Admin) {
            return true;
        }
        return $user->hasRole('Business Owner');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function forceDelete($user, $model): bool
    {
        if ($user instanceof \App\Models\Admin) {
            return true;
        }
        return $user->hasRole('Business Owner');
    }
}
