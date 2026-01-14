<?php

namespace App\Services\AI;

use OpenAI;
use Illuminate\Support\Facades\Log;
use App\Models\MenuItem;
use App\Models\ReservationSetting;
use App\Services\AI\BaseAiService;

class WebsiteGeneratorService extends BaseAiService
{
    public function generateLayout(): array
    {
        $tenant = tenant();
        $reservationSettings = ReservationSetting::getInstance();
        $menuItems = MenuItem::where('is_active', true)->where('is_available', true)->limit(6)->get();

        $prompt = "Generate a full restaurant website layout in JSON format for '{$tenant->name}'.\n";
        $prompt .= "Description: {$tenant->description}\n";
        $prompt .= "Cuisine: " . ($tenant->businessCategory?->name ?? 'Fine Dining') . "\n";
        $prompt .= "Menu Preview: " . $menuItems->map(fn($item) => "{$item->name} (\${$item->base_price})")->implode(', ') . "\n";
        $prompt .= "Location: {$reservationSettings->business_address}\n";
        $prompt .= "Phone: {$reservationSettings->business_phone}\n";
        $prompt .= "Opening Hours: " . json_encode($reservationSettings->business_hours) . "\n";

        $prompt .= "\nThe output MUST be a JSON array of blocks. Each block must have 'id', 'type', 'editable' (bool), and 'data' (object).\n";
        $prompt .= "Block types allowed: hero, about, dynamic_menu, menu_preview, reservation_cta, contact, reservation_form.\n";
        $prompt .= "For 'dynamic_menu', 'data' must include 'title', 'subtitle', and an array of 'category_ids' from the business.\n";
        $prompt .= "The category IDs available for this restaurant are: " . \App\Models\Category::where('is_active', true)->pluck('id')->implode(', ') . "\n";
        $prompt .= "Ensure the copy is professional, elegant, and matches the restaurant's brand.\n";
        $prompt .= "Respond ONLY with the raw JSON array.";

        if (!$this->client) {
            return $this->getFallbackLayout($tenant, $reservationSettings, $menuItems);
        }

        try {
            $response = $this->client->chat()->create([
                'model' => $this->settings->premium_model ?? 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI Website Builder. You generate structured JSON layouts for restaurant websites.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);

            $content = $response->choices[0]->message->content;
            $data = json_decode($content, true);

            // Handle case where AI returns { "blocks": [...] } instead of directly [...]
            return $data['blocks'] ?? $data;

        } catch (\Exception $e) {
            Log::error('AI Website Generation failed: ' . $e->getMessage());
            return $this->getFallbackLayout($tenant, $reservationSettings, $menuItems);
        }
    }

    protected function getFallbackLayout($tenant, $reservationSettings, $menuItems): array
    {
        return [
            [
                'id' => uniqid('hero_'),
                'type' => 'hero',
                'editable' => true,
                'data' => [
                    'headline' => "Experience the Finest {$tenant->name}",
                    'subheadline' => $tenant->description ?: "Discover a world of flavors and exceptional service at the heart of the city.",
                    'cta_text' => 'Reserve a Table',
                    'cta_url' => '#reserve',
                ]
            ],
            [
                'id' => uniqid('about_'),
                'type' => 'about',
                'editable' => true,
                'data' => [
                    'title' => 'Our Story',
                    'content' => "Welcome to {$tenant->name}. We are dedicated to providing an unforgettable dining experience through our passion for fresh ingredients and culinary innovation.",
                ]
            ],
            [
                'id' => uniqid('menu_'),
                'type' => 'dynamic_menu',
                'editable' => true,
                'data' => [
                    'title' => 'Our Signature Menu',
                    'subtitle' => 'Explore our culinary creations.',
                    'category_ids' => \App\Models\Category::where('is_active', true)->limit(3)->pluck('id')->toArray(),
                    'background_color' => '#f8fafc',
                ]
            ],
            [
                'id' => uniqid('cta_'),
                'type' => 'reservation_cta',
                'editable' => true,
                'data' => [
                    'headline' => 'Ready to join us?',
                    'button_text' => 'Book Your Table',
                ]
            ],
            [
                'id' => uniqid('contact_'),
                'type' => 'contact',
                'editable' => true,
                'data' => [
                    'address' => $reservationSettings->business_address ?: '123 Restaurant Row, Food City',
                    'phone' => $reservationSettings->business_phone ?: '+1 (555) 000-0000',
                    'email' => 'hello@' . $tenant->slug . '.com',
                ]
            ],
            [
                'id' => uniqid('form_'),
                'type' => 'reservation_form',
                'editable' => true,
                'data' => [
                    'title' => 'Make a Reservation',
                    'description' => 'Select your date and time to secure your table.',
                ]
            ]
        ];
    }
}
