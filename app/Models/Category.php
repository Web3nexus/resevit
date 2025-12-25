<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Category extends Model
{
    protected $connection = 'tenant';
    // Note: We don't need BelongsToTenant here because this table is inside the tenant DB.
    // BelongsToTenant is for central tables that have a tenant_id column.

    

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
