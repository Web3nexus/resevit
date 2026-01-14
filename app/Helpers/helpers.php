<?php

use App\Models\Tenant;
use App\Services\FeatureManager;

if (!function_exists('has_feature')) {
    /**
     * Check if the current tenant has access to a feature.
     */
    function has_feature(string $featureKey, ?Tenant $tenant = null): bool
    {
        $tenant = $tenant ?? tenant();

        if (!$tenant) {
            return false;
        }

        $result = app(FeatureManager::class)->hasFeature($tenant, $featureKey);

        \Log::debug("has_feature check: {$featureKey} = " . ($result ? 'TRUE' : 'FALSE'));

        return $result;
    }
}

if (!function_exists('get_feature_limit')) {
    /**
     * Get the numeric limit for a feature for the current tenant.
     */
    function get_feature_limit(string $featureKey, ?Tenant $tenant = null): ?int
    {
        $tenant = $tenant ?? tenant();

        if (!$tenant) {
            return 0;
        }

        return app(FeatureManager::class)->getFeatureLimit($tenant, $featureKey);
    }
}
if (!function_exists('format_currency')) {
    /**
     * Format a numeric amount as a currency string.
     */
    function format_currency(float $amount, ?string $currency = null): string
    {
        return app(\App\Services\CurrencyService::class)->format($amount, $currency);
    }
}
