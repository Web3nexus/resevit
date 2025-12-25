<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantBasePolicy
{
    use HandlesAuthorization;

    /**
     * Business Owners and Staff can view anything in their tenant.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    public function view(User $user, $model): bool
    {
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    public function update(User $user, $model): bool
    {
        return $user->hasRole(['Business Owner', 'Staff']);
    }

    public function delete(User $user, $model): bool
    {
        return $user->hasRole(['Business Owner']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole(['Business Owner']);
    }

    public function forceDelete(User $user, $model): bool
    {
        return false; // Typically restricted
    }

    public function restore(User $user, $model): bool
    {
        return $user->hasRole(['Business Owner']);
    }
}
