<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'logo_path',
        'favicon_path',
        'supported_languages',
        'footer_settings',
    ];

    protected $casts = [
        'supported_languages' => 'array',
        'footer_settings' => 'array',
    ];

    public static function current(): self
    {
        $settings = static::firstOrCreate([], [
            'supported_languages' => ['en', 'es', 'fr', 'de', 'ar'],
            'footer_settings' => self::getDefaultFooterSettings(),
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
}
