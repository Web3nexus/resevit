<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\LandingPage;
use App\Models\LandingSection;
use App\Models\LandingItem;
use App\Models\Testimonial;
use App\Models\PricingPlan;
use App\Models\PricingFeature;
use App\Models\Faq;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Features
        $featuresList = [
            ['name' => 'AI Table Optimization', 'description' => 'Automatically maximize floor utilization.'],
            ['name' => 'Omnichannel Reservations', 'description' => 'Book via Web, WhatsApp, FB, and Instagram.'],
            ['name' => 'Staff Scheduling', 'description' => 'Smart shifts based on booking density.'],
            ['name' => 'Real-time Analytics', 'description' => 'Live performance tracking and heatmaps.'],
            ['name' => 'Loyalty Program', 'description' => 'Retain guests with automated rewards.'],
        ];

        foreach ($featuresList as $f) {
            PricingFeature::updateOrCreate(['name' => $f['name']], $f);
        }

        // 2. Create Pricing Plans
        $starter = PricingPlan::updateOrCreate(['slug' => 'starter'], [
            'name' => 'Starter',
            'description' => 'Perfect for small cafes and bistros.',
            'price_monthly' => 29,
            'price_yearly' => 290,
            'is_active' => true,
            'order' => 1,
        ]);

        $pro = PricingPlan::updateOrCreate(['slug' => 'professional'], [
            'name' => 'Professional',
            'description' => 'Advanced features for growing restaurants.',
            'price_monthly' => 79,
            'price_yearly' => 790,
            'is_featured' => true,
            'is_active' => true,
            'order' => 2,
        ]);

        // Link features to plans
        $allFeatures = PricingFeature::all();
        $starter->features()->sync($allFeatures->random(3)->pluck('id'));
        $pro->features()->sync($allFeatures->pluck('id'));

        // 3. Create Testimonials
        Testimonial::updateOrCreate(['name' => 'Marco Rossi'], [
            'role' => 'Owner',
            'company' => 'La Fontana',
            'content' => 'Resevit transformed how we handle our busy weekend rushes. The AI optimization boosted our revenue by 18%.',
            'rating' => 5,
            'is_active' => true,
        ]);

        Testimonial::updateOrCreate(['name' => 'Sarah Jenkins'], [
            'role' => 'Manager',
            'company' => 'The Green Room',
            'content' => 'The message integration is a lifesaver. No more switching between apps to talk to guests.',
            'rating' => 5,
            'is_active' => true,
        ]);

        // 4. Create Home Page
        $homePage = LandingPage::updateOrCreate(['slug' => 'home'], [
            'title' => 'Home',
            'meta_title' => 'Resevit - The Future of Restaurant Management',
            'meta_description' => 'Maximize your restaurant’s potential with AI-driven reservations and management.',
            'is_active' => true,
        ]);

        // Sections
        $sections = [
            [
                'type' => 'announcement_bar',
                'title' => 'New Feature: AI-Powered Table Management is here!',
                'content' => ['link_text' => 'Read more', 'link_url' => '/resources/ai-launch'],
                'order' => 0,
            ],
            [
                'type' => 'hero',
                'title' => 'Maximize Your Restaurant’s Potential',
                'subtitle' => 'THE FUTURE OF DINING',
                'content' => ['description' => 'The all-in-one platform for modern restaurants. Automate bookings, manage staff, and grow your revenue with ease.'],
                'order' => 1,
            ],
            [
                'type' => 'logo_cloud',
                'title' => 'TRUSTED BY TOP ESTABLISHMENTS',
                'order' => 2,
            ],
            [
                'type' => 'features',
                'title' => 'Designed for Scale',
                'subtitle' => 'CORE CAPABILITIES',
                'content' => ['description' => 'Tools that empower your staff and delight your customers at every touchpoint.'],
                'order' => 3,
            ],
            [
                'type' => 'stats',
                'order' => 4,
            ],
            [
                'type' => 'workflow',
                'title' => 'How It Works',
                'subtitle' => 'GET STARTED IN MINUTES',
                'order' => 5,
                'items' => [
                    ['title' => 'Sign Up', 'description' => 'Create your account and claim your restaurant identity.'],
                    ['title' => 'Configure', 'description' => 'Set up your floor plan and connect your social accounts.'],
                    ['title' => 'Grow', 'description' => 'Launch your booking link and watch the reservations roll in.'],
                ]
            ],
            [
                'type' => 'testimonials',
                'order' => 6,
            ],
            [
                'type' => 'pricing',
                'order' => 7,
            ],
            [
                'type' => 'cta_banner',
                'title' => 'Ready to Modernize Your Service?',
                'content' => ['description' => 'Join the elite restaurants using Resevit to provide world-class dining experiences.'],
                'order' => 8,
            ],
        ];

        foreach ($sections as $sData) {
            $items = $sData['items'] ?? null;
            unset($sData['items']);

            $section = $homePage->sections()->updateOrCreate(['type' => $sData['type']], $sData);

            if ($items) {
                foreach ($items as $iData) {
                    $section->items()->updateOrCreate(['title' => $iData['title']], $iData);
                }
            }
        }

        // 5. Create Features Page
        $featuresPage = LandingPage::updateOrCreate(['slug' => 'features'], [
            'title' => 'Features',
            'meta_title' => 'Advanced Features - Resevit',
            'meta_description' => 'Explore the powerful tools that make Resevit the ultimate restaurant management platform.',
            'is_active' => true,
        ]);

        $featuresPage->sections()->updateOrCreate(['type' => 'hero'], [
            'title' => 'Everything You Need to Scale',
            'subtitle' => 'PLATFORM OVERVIEW',
            'content' => ['description' => 'From floor plan management to AI-driven customer insights, Resevit provides a comprehensive suite of tools designed for excellence.'],
            'order' => 1,
        ]);

        $fSec = $featuresPage->sections()->updateOrCreate(['type' => 'features'], [
            'title' => 'Core Capabilities',
            'order' => 2,
        ]);

        $fSec->items()->createMany([
            ['title' => 'Smart Floor Plan', 'description' => 'Drag-and-drop editor to mirror your physical space.', 'icon' => 'fas fa-map'],
            ['title' => 'Real-time Sync', 'description' => 'Updates across all devices instantly.', 'icon' => 'fas fa-sync'],
            ['title' => 'Advanced Reporting', 'description' => 'Drill down into your busiest hours and top servers.', 'icon' => 'fas fa-chart-line'],
        ]);

        // 6. Create Integrations Page
        $integrationsPage = LandingPage::updateOrCreate(['slug' => 'integrations'], [
            'title' => 'Integrations',
            'meta_title' => 'Connect Your Stack - Resevit',
            'meta_description' => 'Resevit plays well with others. Connect your POS, social media, and more.',
            'is_active' => true,
        ]);

        $integrationsPage->sections()->updateOrCreate(['type' => 'connected_tools'], [
            'title' => 'Seamless Connectivity',
            'subtitle' => 'INTEGRATIONS',
            'order' => 1,
        ]);

        // 7. Create About Page
        $aboutPage = LandingPage::updateOrCreate(['slug' => 'about'], [
            'title' => 'About Us',
            'meta_title' => 'Our Story - Resevit',
            'meta_description' => 'Founded by hospitality veterans, Resevit is on a mission to modernize the dining experience.',
            'is_active' => true,
        ]);

        $aboutPage->sections()->updateOrCreate(['type' => 'stats'], [
            'order' => 1,
        ]);

        $aboutPage->sections()->updateOrCreate(['type' => 'features'], [
            'title' => 'Our Core Values',
            'content' => ['description' => 'Innovation, Reliability, and Passion for Hospitality.'],
            'order' => 2,
        ]);

        // 8. Create Sample Resource
        $aiLaunch = LandingPage::updateOrCreate(['slug' => 'ai-launch'], [
            'title' => 'AI Launch Announcement',
            'is_active' => true,
        ]);

        $aiLaunch->sections()->updateOrCreate(['type' => 'hero'], [
            'title' => 'Transforming Dining with AI',
            'subtitle' => 'PRODUCT UPDATE',
            'content' => ['description' => 'We are excited to announce the launch of our new AI-powered table management system...'],
            'order' => 1,
        ]);
    }
}
