<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'logo_path',
        'logo_dark_path',
        'favicon_path',
        'supported_languages',
        'footer_settings',
        'error_pages',
        'promotion_settings',
        'landing_settings',
        'legal_settings',
        'onboarding_settings',
        'stripe_settings',
        'stripe_mode',
        'plugin_settings',
        'email_settings',
    ];

    protected $casts = [
        'supported_languages' => 'array',
        'footer_settings' => 'array',
        'error_pages' => 'array',
        'promotion_settings' => 'array',
        'landing_settings' => 'array',
        'legal_settings' => 'array',
        'onboarding_settings' => 'array',
        'stripe_settings' => 'array',
        'stripe_mode' => 'string',
        'plugin_settings' => 'array',
        'email_settings' => 'array',
    ];

    public static function current(): self
    {
        $settings = static::firstOrCreate([], [
            'supported_languages' => ['en', 'es', 'fr', 'de', 'ar'],
            'footer_settings' => self::getDefaultFooterSettings(),
            'landing_settings' => [
                'hero_badge' => 'THE FUTURE OF DINING',
                'hero_title' => 'Maximize Your Restaurant’s <span class="text-brand-accent">Potential</span>',
                'hero_subtitle' => "Streamline reservations, optimize staff schedules, and delight customers with the world's most advanced restaurant management platform.",
                'hero_cta_text' => 'Get Started Free',
                'hero_cta_url' => '/register',
                'active_theme' => 'default',
            ],
            'promotion_settings' => [
                'min_withdrawal_amount' => 50,
                'affiliate_enabled' => true,
            ],
        ]);

        return $settings;
    }

    public static function getDefaultFooterSettings(): array
    {
        return [
            'legal' => [
                ['label' => 'Terms of Service', 'url' => '/terms', 'is_visible' => true],
                ['label' => 'Privacy Policy', 'url' => '/privacy', 'is_visible' => true],
                ['label' => 'GDPR', 'url' => '/gdpr', 'is_visible' => true],
                ['label' => 'DMCA', 'url' => '/dmca', 'is_visible' => true],
            ],
            'others' => [
                ['label' => 'Features', 'url' => '/features', 'is_visible' => true],
                ['label' => 'System Status', 'url' => '/status', 'is_visible' => true],
                ['label' => 'Log In', 'url' => '/login', 'is_visible' => true],
                ['label' => 'Documentation', 'url' => '/docs', 'is_visible' => true],
            ],
        ];
    }

    public function getSupportedLanguages(): array
    {
        return $this->supported_languages ?? ['en', 'es', 'fr', 'de', 'ar'];
    }

    public function getFooterLinks(string $section): array
    {
        $settings = $this->footer_settings ?? self::getDefaultFooterSettings();

        return array_filter($settings[$section] ?? [], fn($link) => $link['is_visible'] ?? true);
    }

    /**
     * Get the active Stripe configuration based on current mode
     */
    public function getActiveStripeConfig(): array
    {
        $mode = $this->stripe_mode ?? 'test';
        $settings = $this->stripe_settings ?? [];

        return $settings[$mode] ?? [
            'publishable_key' => '',
            'secret_key' => '',
            'webhook_secret' => '',
        ];
    }

    /**
     * Get the Stripe publishable key for the current environment
     */
    public function getStripePublishableKey(): ?string
    {
        $config = $this->getActiveStripeConfig();

        return $config['publishable_key'] ?? null;
    }

    /**
     * Get the Stripe secret key for the current environment
     */
    public function getStripeSecretKey(): ?string
    {
        $config = $this->getActiveStripeConfig();

        return $config['secret_key'] ?? null;
    }

    /**
     * Get the Stripe webhook secret for the current environment
     */
    public function getStripeWebhookSecret(): ?string
    {
        $config = $this->getActiveStripeConfig();

        return $config['webhook_secret'] ?? null;
    }

    /**
     * Check if Stripe is in test mode
     */
    public function isStripeTestMode(): bool
    {
        return ($this->stripe_mode ?? 'test') === 'test';
    }

    /**
     * Check if Stripe is in live mode
     */
    public function isStripeLiveMode(): bool
    {
        return ($this->stripe_mode ?? 'test') === 'live';
    }
}
