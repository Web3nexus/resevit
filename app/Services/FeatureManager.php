<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\PricingFeature;
use Illuminate\Support\Facades\Cache;

class FeatureManager
{
    /**
     * Check if a tenant has access to a feature.
     */
    public function hasFeature(Tenant $tenant, string $featureKey): bool
    {
        $plan = $this->getTenantPlan($tenant);

        if (!$plan) {
            return false;
        }

        // Super Admin or Enterprise plan bypass checks (slug based)
        if ($plan->slug === 'enterprise') {
            return true;
        }

        return Cache::remember("tenant_{$tenant->id}_feature_{$featureKey}", 3600, function () use ($plan, $featureKey) {
            return $plan->features()
                ->where('feature_key', $featureKey)
                ->wherePivot('is_included', true)
                ->exists();
        });
    }

    /**
     * Get the numeric limit for a feature.
     */
    public function getFeatureLimit(Tenant $tenant, string $featureKey): ?int
    {
        $plan = $this->getTenantPlan($tenant);

        if (!$plan || $plan->slug === 'enterprise') {
            return null; // Unlimited
        }

        return Cache::remember("tenant_{$tenant->id}_limit_{$featureKey}", 3600, function () use ($plan, $featureKey) {
            $feature = $plan->features()
                ->where('feature_key', $featureKey)
                ->first();

            return $feature ? $feature->pivot->limit_value : 0;
        });
    }

    /**
     * Get the tenant's current pricing plan.
     */
    protected function getTenantPlan(Tenant $tenant)
    {
        return $tenant->plan;
    }

    /**
     * Check if the tenant's trial has expired.
     */
    public function isTrialExpired(Tenant $tenant): bool
    {
        if (!$tenant->trial_ends_at) {
            return false;
        }

        return $tenant->trial_ends_at->isPast();
    }
}
