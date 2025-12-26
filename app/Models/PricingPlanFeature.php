<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PricingPlanFeature extends Pivot
{
    protected $table = 'pricing_plan_feature';

    public $incrementing = true; // IMPORTANT if the pivot table has an 'id' column

    protected $fillable = [
        'pricing_plan_id',
        'pricing_feature_id',
        'is_included',
        'value',
        'limit_value',
    ];

    protected $casts = [
        'is_included' => 'boolean',
        'limit_value' => 'integer',
    ];

    public function feature()
    {
        return $this->belongsTo(PricingFeature::class, 'pricing_feature_id');
    }

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_id');
    }
}
