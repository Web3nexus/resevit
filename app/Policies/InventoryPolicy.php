<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InventoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return has_feature('inventory');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Inventory $inventory): bool
    {
        return has_feature('inventory');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return has_feature('inventory');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Inventory $inventory): bool
    {
        return has_feature('inventory');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Inventory $inventory): bool
    {
        return has_feature('inventory');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Inventory $inventory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Inventory $inventory): bool
    {
        return false;
    }
}
