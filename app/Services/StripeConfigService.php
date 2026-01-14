<?php

namespace App\Services;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Config;

class StripeConfigService
{
    /**
     * Get the current Stripe mode (test or live)
     */
    public static function getCurrentMode(): string
    {
        $settings = PlatformSetting::current();
        return $settings->stripe_mode ?? 'test';
    }

    /**
     * Get the active Stripe configuration for the current environment
     */
    public static function getActiveConfig(): array
    {
        $settings = PlatformSetting::current();
        return $settings->getActiveStripeConfig();
    }

    /**
     * Set Stripe configuration in Laravel config at runtime
     * This should be called before making any Stripe API calls
     */
    public static function setStripeConfig(): void
    {
        $config = self::getActiveConfig();

        if (!empty($config['secret_key'])) {
            Config::set('cashier.secret', $config['secret_key']);
        }

        if (!empty($config['publishable_key'])) {
            Config::set('cashier.key', $config['publishable_key']);
        }

        if (!empty($config['webhook_secret'])) {
            Config::set('cashier.webhook.secret', $config['webhook_secret']);
        }
    }

    /**
     * Check if Stripe is in test mode
     */
    public static function isTestMode(): bool
    {
        return self::getCurrentMode() === 'test';
    }

    /**
     * Check if Stripe is in live mode
     */
    public static function isLiveMode(): bool
    {
        return self::getCurrentMode() === 'live';
    }

    /**
     * Validate Stripe key format
     * 
     * @param string $key The Stripe key to validate
     * @param string $type The type of key: 'publishable', 'secret', or 'webhook'
     * @return bool
     */
    public static function validateKey(string $key, string $type): bool
    {
        if (empty($key)) {
            return false;
        }

        return match ($type) {
            'publishable' => str_starts_with($key, 'pk_test_') || str_starts_with($key, 'pk_live_'),
            'secret' => str_starts_with($key, 'sk_test_') || str_starts_with($key, 'sk_live_'),
            'webhook' => str_starts_with($key, 'whsec_'),
            default => false,
        };
    }

    /**
     * Validate that keys match the current environment mode
     * 
     * @param array $keys Array with 'publishable_key', 'secret_key', 'webhook_secret'
     * @param string $mode 'test' or 'live'
     * @return array Array of validation errors (empty if valid)
     */
    public static function validateKeysForMode(array $keys, string $mode): array
    {
        $errors = [];
        $prefix = $mode === 'live' ? 'live' : 'test';

        if (!empty($keys['publishable_key']) && !str_contains($keys['publishable_key'], $prefix)) {
            $errors[] = "Publishable key must be for {$mode} mode (should start with pk_{$prefix}_)";
        }

        if (!empty($keys['secret_key']) && !str_contains($keys['secret_key'], $prefix)) {
            $errors[] = "Secret key must be for {$mode} mode (should start with sk_{$prefix}_)";
        }

        return $errors;
    }

    /**
     * Get a human-readable label for the current mode
     */
    public static function getModeLabel(): string
    {
        return self::isLiveMode() ? 'Live Mode' : 'Test Mode';
    }

    /**
     * Get a color indicator for the current mode (for UI)
     */
    public static function getModeColor(): string
    {
        return self::isLiveMode() ? 'danger' : 'warning';
    }
}
