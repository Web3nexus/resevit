<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;

trait TenantAuthenticatable
{
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (tenancy()->initialized && !$model->tenant_id) {
                $model->tenant_id = tenancy()->tenant->id;
            }
        });
    }
}
