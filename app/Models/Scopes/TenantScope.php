<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // If tenancy is initialized, we filter by the current tenant ID.
        // This assumes the model has a tenant_id column.
        if (function_exists('tenant') && tenant('id')) {
            $builder->where($model->getTable() . '.tenant_id', tenant('id'));
        }
    }
}
