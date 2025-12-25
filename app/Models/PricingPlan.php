<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'trial_days',
        'is_trial_available',
        'is_free',
        'stripe_id',
        'cta_text',
        'cta_url',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_trial_available' => 'boolean',
        'is_free' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
    ];

    public function features()
    {
        return $this->belongsToMany(PricingFeature::class, 'pricing_plan_feature')
            ->withPivot('is_included', 'value')
            ->withTimestamps();
    }
}
