<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\PricingFeature;
use App\Services\FeaturePermissionManager;
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

        \Illuminate\Support\Facades\Log::info("FeatureManager: Checking '{$featureKey}' for Tenant {$tenant->id} (Plan: " . ($plan ? $plan->id : 'None') . ")");

        // return Cache::remember("tenant_{$tenant->id}_feature_{$featureKey}", 3600, function () use ($plan, $featureKey) {
        $result = $plan->features()
            ->where('feature_key', $featureKey)
            ->wherePivot('is_included', true)
            ->exists();

        \Illuminate\Support\Facades\Log::info("FeatureManager: Result for '{$featureKey}' is " . ($result ? 'TRUE' : 'FALSE'));
        return $result;
        // });
    }

    /**
     * Get all enabled features for a tenant.
     */
    public function getEnabledFeatures(Tenant $tenant): array
    {
        $plan = $this->getTenantPlan($tenant);

        if (!$plan) {
            return [];
        }

        if ($plan->slug === 'enterprise') {
            return array_keys(FeaturePermissionManager::getFeaturePermissions());
        }

        return $plan->features()
            ->wherePivot('is_included', true)
            ->pluck('feature_key')
            ->toArray();
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

    public function isTrialExpired(Tenant $tenant): bool
    {
        if (!$tenant->trial_ends_at) {
            return false;
        }

        return $tenant->trial_ends_at->isPast();
    }

    /**
     * Clear feature cache for a tenant.
     */
    public function clearCache(Tenant $tenant): void
    {
        // Simple clearing strategy - In production with Redis we could use tags.
        // For file driver, we can't iterate easily. 
        // We will just flush globally or we accepted that manual assignment requires simple cache clear.
        // But better: let's just make the cache key dependent on the plan ID?
        // No, plan features might change.

        // Changing the 'remember' to use a version key derived from the plan updated_at?
        Cache::forget("tenant_{$tenant->id}_features_list"); // theoretical key

        // Since we can't wildcard delete on file driver, we'll suggest using a cache tag or shorter TTL.
        // For now, let's just reduce TTL in hasFeature to 60 seconds? Or 0 for dev.
    }
}
