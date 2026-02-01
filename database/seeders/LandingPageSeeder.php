<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use App\Models\PricingFeature;
use App\Models\PricingPlan;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

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
                ],
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

            $section = $homePage->sections()->updateOrCreate(['type' => $sData['type']], array_merge($sData, ['is_active' => true]));

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

        $aboutPage->sections()->updateOrCreate(['type' => 'logo_cloud'], [
            'title' => 'Trusted by Leading Brands',
            'subtitle' => 'PARTNERSHIPS',
            'order' => 3,
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

        // 9. Create Legal Documents
        $legalDocs = [
            [
                'slug' => 'terms',
                'title' => 'Terms of Service',
                'content' => '<h1>Terms of Service</h1><p>Welcome to Resevit. By accessing or using our platform, you agree to be bound by these terms. We\'ve tried to keep them straightforward because we know nobody likes reading pages of legalese.</p><h3>1. Using Our Platform</h3><p>Resevit provides a suite of tools for restaurant management. You agree to use these tools for their intended purpose and not to engage in any activity that might harm the platform or its users. This includes attempts to reverse-engineer our software or disrupt our services.</p><h3>2. Restaurant Responsibilities</h3><p>As a restaurant owner, you are responsible for the accuracy of your listings, menus, and available slots. Resevit is a facilitator, but the ultimate dining experience is between you and your customers.</p><h3>3. Booking & Cancellations</h3><p>Our platform enables real-time bookings. You agree to honor reservations made through the system or communicate promptly with customers if changes are necessary. Misuse of the booking system may lead to account suspension.</p><h3>4. Payments & Fees</h3><p>Subscribed businesses agree to pay all applicable fees according to their chosen plan. Fees are non-refundable unless otherwise specified. We use secure third-party processors like Stripe to handle your financial data.</p><h3>5. Limitation of Liability</h3><p>While we strive for 100% uptime and a bug-free experience, Resevit is provided \'as-is\'. We are not liable for indirect damages or lost profits resulting from the use of our service.</p><h3>6. Updates to Terms</h3><p>We might update these terms from time to time as our platform grows. We\'ll notify you of any significant changes via email or dashboard notifications.</p>',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy Policy',
                'content' => '<h1>Privacy Policy</h1><p>At Resevit, we take your privacy seriously. We\'re not in the business of selling your data; we\'re in the business of helping your restaurant thrive.</p><h3>What Data We Collect</h3><p>We collect information necessary to run the service, including restaurant details, staff schedules, and reservation data (like diner names and contact info). We also collect technical data like IP addresses and browser types to help us optimize performance.</p><h3>How We Use Your Data</h3><p>Your data is used solely to provide and improve the Resevit experience. This includes facilitating bookings, generating analytics, and sending system-related notifications. We do not share your sensitive business data with third parties for marketing purposes.</p><h3>Data Security</h3><p>We use industry-standard encryption and security protocols to keep your information safe. Our database is monitored 24/7 to prevent unauthorized access.</p><h3>Your Choices</h3><p>You can view, edit, or delete your account information at any time through your dashboard. If you have questions about your data, our team is always here to help.</p>',
            ],
            [
                'slug' => 'cookie-policy',
                'title' => 'Cookie Policy',
                'content' => '<h1>Cookie Policy</h1><p>We use cookies to enhance your experience. By using our website, you agree to our use of cookies.</p>', // Simplified placeholder as partial content found
            ],
            [
                'slug' => 'gdpr',
                'title' => 'GDPR Compliance',
                'content' => '<h1>GDPR Compliance</h1><p>Resevit is fully committed to the principles of the General Data Protection Regulation (GDPR). We believe transparency and user control are fundamental rights.</p><h3>Data Subject Rights</h3><p>If you are based in the EU, you have specific rights under GDPR, including the right to access, rectify, or erase your personal data. You can exercise these rights directly through your account settings or by contacting our Data Protection Officer.</p><h3>Data Processor Agreement</h3><p>For restaurants using Resevit to process diner data, we act as a Data Processor. Our terms include standard contractual clauses to ensure your customers\' data is handled with the highest level of care.</p><h3>Data Archiving</h3><p>We only keep personal data for as long as it\'s needed to fulfill the purposes for which it was collected. Inactive accounts are periodically reviewed for data minimization.</p>',
            ],
        ];

        foreach ($legalDocs as $doc) {
            $page = LandingPage::updateOrCreate(['slug' => $doc['slug']], [
                'title' => $doc['title'],
                'is_active' => true,
            ]);

            $page->sections()->updateOrCreate(['type' => 'text_content'], [
                'title' => $doc['title'],
                'content' => ['body' => $doc['content']],
                'order' => 0,
                'is_active' => true,
            ]);
        }
    }
}
