<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function businesses()
    {
        return $this->hasMany(Tenant::class, 'business_category_id');
    }
}
