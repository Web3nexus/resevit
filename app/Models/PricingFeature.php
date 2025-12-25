<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingFeature extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'name',
        'feature_key',
        'description',
        'category',
        'is_billable',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_billable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function plans()
    {
        return $this->belongsToMany(PricingPlan::class, 'pricing_plan_feature')
            ->withPivot('is_included', 'value')
            ->withTimestamps();
    }
}
