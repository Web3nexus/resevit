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
        'monthly_ai_credits',
        'yearly_discount_percentage',
        'trial_days',
        'is_trial_available',
        'is_free',
        'stripe_id',
        'stripe_yearly_id',
        'stripe_product_id_test',
        'stripe_product_id_live',
        'stripe_price_id_test',
        'stripe_price_id_live',
        'stripe_yearly_price_id_test',
        'stripe_yearly_price_id_live',
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
        'monthly_ai_credits' => 'decimal:6',
        'yearly_discount_percentage' => 'decimal:2',
    ];

    public function features()
    {
        return $this->belongsToMany(PricingFeature::class, 'pricing_plan_feature')
            ->withPivot('is_included', 'value', 'limit_value')
            ->withTimestamps();
    }

    public function planFeatures()
    {
        return $this->hasMany(PricingPlanFeature::class, 'pricing_plan_id');
    }

    public function websiteTemplates()
    {
        return $this->belongsToMany(WebsiteTemplate::class, 'pricing_plan_website_template');
    }

    /**
     * Get the Stripe product ID for the current environment
     */
    public function getStripeProductId(): ?string
    {
        $settings = \App\Models\PlatformSetting::current();
        $mode = $settings->stripe_mode ?? 'test';

        return $mode === 'live'
            ? $this->stripe_product_id_live
            : $this->stripe_product_id_test;
    }

    /**
     * Get the Stripe price ID for the current environment and billing cycle
     *
     * @param  string  $billingCycle  'monthly' or 'yearly'
     */
    public function getStripePriceId(string $billingCycle = 'monthly'): ?string
    {
        $settings = \App\Models\PlatformSetting::current();
        $mode = $settings->stripe_mode ?? 'test';

        if ($billingCycle === 'yearly') {
            return $mode === 'live'
                ? $this->stripe_yearly_price_id_live
                : ($this->stripe_yearly_price_id_test ?? $this->stripe_yearly_id);
        }

        return $mode === 'live'
            ? $this->stripe_price_id_live
            : ($this->stripe_price_id_test ?? $this->stripe_id);
    }
}
