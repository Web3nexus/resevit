<?php

namespace App\Policies;

use App\Models\Promotion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PromotionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return has_feature('promotions');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Promotion $promotion): bool
    {
        return has_feature('promotions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return has_feature('promotions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Promotion $promotion): bool
    {
        return has_feature('promotions');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Promotion $promotion): bool
    {
        return has_feature('promotions');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Promotion $promotion): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Promotion $promotion): bool
    {
        return false;
    }
}
