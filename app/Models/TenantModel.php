<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

abstract class TenantModel extends Model
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Apply the global tenant scope
        static::addGlobalScope(new TenantScope());

        // Automatically assign tenant_id when creating a new record
        static::creating(function ($model) {
            if (function_exists('tenant') && tenant('id') && !$model->tenant_id) {
                $model->tenant_id = tenant('id');
            }
        });
    }
}
