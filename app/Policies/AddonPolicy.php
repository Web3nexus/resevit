<?php

namespace App\Policies;

class AddonPolicy extends TenantBasePolicy
{
    public function viewAny(\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {
        if ($user instanceof \App\Models\Admin)
            return true;

        return has_feature('menu');
    }
}
