<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Features Page
        $featuresPage = \App\Models\LandingPage::where('slug', 'features')->first();
        if ($featuresPage) {
            $this->seedFeaturesPage($featuresPage);
        }

        // About Page
        $aboutPage = \App\Models\LandingPage::where('slug', 'about')->first();
        if ($aboutPage) {
            $this->seedAboutPage($aboutPage);
        }
    }

    private function seedFeaturesPage(\App\Models\LandingPage $page)
    {
        // Clear existing to avoid dupes if re-running
        $page->sections()->delete();

        // 1. Page Header (Formerly Hero)
        $hero = $page->sections()->create([
            'type' => 'page_header',
            'title' => 'Everything You Need to <span class="text-brand-accent">Scale</span> Your Restaurant',
            'subtitle' => 'PLATFORM OVERVIEW',
            'content' => ['description' => 'Resevit provides a comprehensive suite of tools designed to handle every aspect of modern hospitality, from the kitchen to the customer\'s doorstep.'],
            'order' => 1,
            'is_active' => true,
        ]);

        // 2. Core Features (3 Cards)
        $features = $page->sections()->create([
            'type' => 'features',
            'title' => 'Core Capabilities',
            'subtitle' => 'BUILT FOR SPEED',
            'content' => ['description' => 'Our platform is built on the lightning-fast Resevit core, ensuring your staff spends less time at the terminal and more time with guests.'],
            'order' => 2,
            'is_active' => true,
        ]);

        $features->items()->createMany([
            [
                'title' => 'Smart Reservations',
                'description' => 'AI-driven table management that maximizes occupancy and eliminates double bookings.',
                'icon' => 'heroicon-o-calendar-days',
                'order' => 1,
            ],
            [
                'title' => 'Staff Orchestration',
                'description' => 'Automated scheduling and real-time performance tracking for a more efficient team.',
                'icon' => 'heroicon-o-users',
                'order' => 2,
            ],
            [
                'title' => 'Deep Analytics',
                'description' => 'Gain actionable insights into your sales, customer behavior, and inventory trends.',
                'icon' => 'heroicon-o-chart-bar',
                'order' => 3,
            ],
        ]);

        // 3. Workflow
        $page->sections()->create([
            'type' => 'workflow',
            'title' => 'Operational Excellence',
            'subtitle' => 'THE RESEVIT FLOW',
            'content' => ['description' => 'See how Resevit transforms your daily operations from start to finish.'],
            'order' => 3,
            'is_active' => true,
        ]);

        // 4. CTA
        $page->sections()->create([
            'type' => 'cta_banner',
            'title' => 'Ready to Transform Your Business?',
            'content' => [
                'description' => 'Join thousands of restaurants already using Resevit to grow their revenue.',
                'button_text' => 'Get Started Now',
                'button_url' => '/register'
            ],
            'order' => 4,
            'is_active' => true,
        ]);
    }

    private function seedAboutPage(\App\Models\LandingPage $page)
    {
        $page->sections()->delete();

        // 1. Page Header (Formerly Hero)
        $page->sections()->create([
            'type' => 'page_header',
            'title' => 'We are on a Mission to <span class="text-brand-accent">Empower</span> Hospitality',
            'subtitle' => 'ABOUT US',
            'content' => ['description' => 'Resevit was founded with a simple goal: to provide restaurants with the same level of technology that global chains use, without the complexity.'],
            'order' => 1,
            'is_active' => true,
        ]);

        // 2. Stats
        $stats = $page->sections()->create([
            'type' => 'stats',
            'title' => 'Our Impact in Numbers',
            'subtitle' => 'BY THE NUMBERS',
            'order' => 2,
            'is_active' => true,
        ]);

        $stats->items()->createMany([
            [
                'title' => '10K+',
                'subtitle' => 'Active Restaurants',
                'order' => 1,
            ],
            [
                'title' => '50M+',
                'subtitle' => 'Reservations Handled',
                'order' => 2,
            ],
            [
                'title' => '99.9%',
                'subtitle' => 'Uptime Guarantee',
                'order' => 3,
            ],
        ]);

        // 3. Values (using features type)
        $values = $page->sections()->create([
            'type' => 'features',
            'title' => 'Our Core Values',
            'subtitle' => 'WHAT DRIVES US',
            'content' => ['description' => 'We believe that technology should be invisible, enabling you to focus on what you do best: providing great food and service.'],
            'order' => 3,
            'is_active' => true,
        ]);

        $values->items()->createMany([
            [
                'title' => 'Integrity First',
                'description' => 'We are honest, transparent, and ethical in all our dealings.',
                'icon' => 'heroicon-o-shield-check',
                'order' => 1,
            ],
            [
                'title' => 'Innovation Daily',
                'description' => 'We constantly push boundaries to bring you the best tools.',
                'icon' => 'heroicon-o-light-bulb',
                'order' => 2,
            ],
            [
                'title' => 'Customer Heroics',
                'description' => 'Your success is our success. We go the extra mile.',
                'icon' => 'heroicon-o-heart',
                'order' => 3,
            ],
        ]);

        // 4. Logo Cloud
        $page->sections()->create([
            'type' => 'logo_cloud',
            'title' => 'Trusted by Leading Brands',
            'subtitle' => 'PARTNERSHIPS',
            'order' => 4,
            'is_active' => true,
        ]);
    }
}
