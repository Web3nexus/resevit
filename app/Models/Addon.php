<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Addon extends Model
{
    protected $connection = 'tenant';
    

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'addon_menu_item');
    }
}
