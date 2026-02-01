<?php

namespace App\Services;

use App\Models\ReservationSetting;
use App\Models\Tenant;
use Illuminate\Support\Str;

class WebsiteGeneratorService
{
    /**
     * Generate a structured website content based on business details.
     */
    public function generate(Tenant $tenant): array
    {
        \Illuminate\Support\Facades\Log::info('WebsiteGeneratorService: Generating for tenant: '.$tenant->slug);

        $settings = ReservationSetting::first();
        $category = $tenant->businessCategory?->name ?? 'Service';
        $description = $tenant->description ?? "Welcome to {$tenant->name}, your premier {$category} destination.";

        \Illuminate\Support\Facades\Log::info('WebsiteGeneratorService: Category detected: '.$category);

        $sections = [
            $this->generateNav($tenant),
            $this->generateHero($tenant, $settings, $category),
            $this->generateAbout($tenant, $description),
            $this->generateMenu($tenant),
            $this->generateFeatures($tenant, $category),
            $this->generateContact($tenant, $settings),
            $this->generateFooter($tenant),
        ];

        return [
            'sections' => $sections,
            'settings' => [
                'primary_color' => '#0B132B',
                'font_family' => 'Inter',
            ],
        ];
    }

    protected function generateNav(Tenant $tenant): array
    {
        return [
            'type' => 'nav',
            'id' => Str::uuid()->toString(),
            'content' => [
                'logo_text' => $tenant->name,
                'links' => [
                    ['label' => 'Home', 'url' => '#hero'],
                    ['label' => 'About', 'url' => '#about'],
                    ['label' => 'Menu', 'url' => '#menu'],
                    ['label' => 'Contact', 'url' => '#contact'],
                ],
            ],
        ];
    }

    protected function generateHero(Tenant $tenant, ?ReservationSetting $settings, string $category): array
    {
        return [
            'type' => 'hero',
            'id' => 'hero', // Fixed ID for anchoring
            'content' => [
                'title' => "Modern {$category} Experience",
                'subtitle' => "Welcome to {$tenant->name}. We serve the finest quality ingredients in a beautiful atmosphere.",
                'button_text' => 'Make a Reservation',
                'button_link' => '/reservations',
                'background_image' => $tenant->cover_image ?? null,
            ],
        ];
    }

    protected function generateAbout(Tenant $tenant, string $description): array
    {
        return [
            'type' => 'about',
            'id' => 'about', // Fixed ID for anchoring
            'content' => [
                'title' => "Discover {$tenant->name}",
                'text' => $description.' Our passion for excellence drives everything we do.',
                'image' => null,
            ],
        ];
    }

    protected function generateMenu(Tenant $tenant): array
    {
        return [
            'type' => 'features', // Reusing features block structure for menu for now
            'id' => 'menu',
            'content' => [
                'title' => 'Signature Dishes',
                'items' => [
                    ['title' => 'Chef\'s Special', 'text' => 'Hand-crafted with seasonal ingredients and traditional techniques.'],
                    ['title' => 'House Favorite', 'text' => 'Our most popular dish, loved by locals and visitors alike.'],
                    ['title' => 'Seasonal Delight', 'text' => 'Fresh, vibrant flavors that celebrate the current season.'],
                ],
            ],
        ];
    }

    protected function generateFeatures(Tenant $tenant, string $category): array
    {
        $features = [
            ['title' => 'Exquisite Flavors', 'text' => 'Our chefs use only the freshest, locally-sourced ingredients.'],
            ['title' => 'Elegant Ambiance', 'text' => 'Enjoy your meal in a sophisticated and comfortable setting.'],
            ['title' => 'Exceptional Service', 'text' => 'Our dedicated staff ensures an unforgettable dining experience.'],
        ];

        return [
            'type' => 'features',
            'id' => Str::uuid()->toString(),
            'content' => [
                'title' => 'The Resevit Experience',
                'items' => $features,
            ],
        ];
    }

    protected function generateContact(Tenant $tenant, ?ReservationSetting $settings): array
    {
        return [
            'type' => 'contact',
            'id' => 'contact',
            'content' => [
                'title' => 'Join Us Today',
                'address' => $settings?->business_address ?? '123 Restaurant Row, Food City',
                'phone' => $settings?->business_phone ?? $tenant->mobile,
                'email' => $tenant->owner?->email,
            ],
        ];
    }

    protected function generateFooter(Tenant $tenant): array
    {
        return [
            'type' => 'footer',
            'id' => Str::uuid()->toString(),
            'content' => [
                'text' => '© '.date('Y')." {$tenant->name}. Powered by Resevit.",
                'social_links' => [],
            ],
        ];
    }
}
