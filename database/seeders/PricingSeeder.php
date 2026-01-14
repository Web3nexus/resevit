<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Features
        $features = [
            ['name' => 'Reservations', 'feature_key' => 'reservations', 'category' => 'Operations'],
            ['name' => 'POS System', 'feature_key' => 'pos', 'category' => 'Operations'],
            ['name' => 'Menu Management', 'feature_key' => 'menu', 'category' => 'Operations'],
            ['name' => 'Staff Management', 'feature_key' => 'staff', 'category' => 'Operations'],
            ['name' => 'Marketing Campaigns', 'feature_key' => 'marketing', 'category' => 'Marketing'],
            ['name' => 'AI Response Assistant', 'feature_key' => 'ai_assistant', 'category' => 'AI'],
            ['name' => 'AI Content Generator', 'feature_key' => 'ai_generator', 'category' => 'AI'],
            ['name' => 'Analytics Dashboard', 'feature_key' => 'analytics', 'category' => 'Reporting'],
            ['name' => 'Messenger Integration', 'feature_key' => 'messaging', 'category' => 'Customer Care'],
            ['name' => 'AI Table Optimization', 'feature_key' => 'ai_optimization', 'category' => 'AI'],
            ['name' => 'Omnichannel Reservations', 'feature_key' => 'omnichannel_reservations', 'category' => 'Operations'],
            ['name' => 'Staff Scheduling', 'feature_key' => 'staff_scheduling', 'category' => 'Operations'],
            ['name' => 'Investment Opportunities', 'feature_key' => 'investment_opportunities', 'category' => 'Finance'],
            ['name' => 'Floor Plan & Rooms', 'feature_key' => 'rooms', 'category' => 'Operations'],
            ['name' => 'Live Monitoring', 'feature_key' => 'live_monitoring', 'category' => 'Operations'],
            ['name' => 'Support Tickets', 'feature_key' => 'support_tickets', 'category' => 'Customer Care'],
            ['name' => 'Staff Chat', 'feature_key' => 'staff_chat', 'category' => 'Communication'],
            ['name' => 'Audit Logs', 'feature_key' => 'audit_logs', 'category' => 'Security'],
            ['name' => 'Multi-Business Management', 'feature_key' => 'multi_business', 'category' => 'Operations'],
            ['name' => 'Directory Listing', 'feature_key' => 'directory_listing', 'category' => 'Marketing'],
            ['name' => 'AI Website Builder', 'feature_key' => 'website_builder', 'category' => 'Marketing'],
            ['name' => 'Custom Domain', 'feature_key' => 'custom_domain', 'category' => 'Marketing'],
            ['name' => 'Whitelabeling', 'feature_key' => 'whitelabel', 'category' => 'Branding'],
            ['name' => 'Wallet & Finance', 'feature_key' => 'finance', 'category' => 'Finance'],
            ['name' => 'Staff Payouts', 'feature_key' => 'staff_payouts', 'category' => 'Finance'],
        ];

        foreach ($features as $f) {
            \App\Models\PricingFeature::updateOrCreate(['feature_key' => $f['feature_key']], $f);
        }

        // 2. Create Plans
        $starter = \App\Models\PricingPlan::updateOrCreate(['slug' => 'starter'], [
            'name' => 'Starter',
            'description' => 'Perfect for small restaurants starting out.',
            'price_monthly' => 29.00,
            'price_yearly' => 290.00,
            'monthly_ai_credits' => 5000,
            'trial_days' => 7,
            'stripe_id' => 'price_STARTER_PLACEHOLDER', // Legacy - kept for backward compatibility
            'stripe_price_id_test' => 'price_STARTER_TEST_PLACEHOLDER',
            'stripe_price_id_live' => null, // To be configured in production
            'order' => 1,
        ]);

        $growth = \App\Models\PricingPlan::updateOrCreate(['slug' => 'growth'], [
            'name' => 'Growth',
            'description' => 'Scale your restaurant with marketing and AI.',
            'price_monthly' => 79.00,
            'price_yearly' => 790.00,
            'monthly_ai_credits' => 25000,
            'stripe_id' => 'price_GROWTH_PLACEHOLDER', // Legacy - kept for backward compatibility
            'stripe_price_id_test' => 'price_GROWTH_TEST_PLACEHOLDER',
            'stripe_price_id_live' => null, // To be configured in production
            'order' => 2,
        ]);

        $pro = \App\Models\PricingPlan::updateOrCreate(['slug' => 'pro'], [
            'name' => 'Pro',
            'description' => 'For busy restaurants that need advanced POS and analytics.',
            'price_monthly' => 149.00,
            'price_yearly' => 1490.00,
            'monthly_ai_credits' => 75000,
            'stripe_id' => 'price_PRO_PLACEHOLDER', // Legacy - kept for backward compatibility
            'stripe_price_id_test' => 'price_PRO_TEST_PLACEHOLDER',
            'stripe_price_id_live' => null, // To be configured in production
            'order' => 3,
        ]);

        $enterprise = \App\Models\PricingPlan::updateOrCreate(['slug' => 'enterprise'], [
            'name' => 'Enterprise',
            'description' => 'Custom solutions for high-volume establishments.',
            'price_monthly' => 499.00,
            'price_yearly' => 4990.00,
            'monthly_ai_credits' => 300000,
            'order' => 4,
        ]);

        // 3. Map Features to Plans
        $allFeatures = \App\Models\PricingFeature::all();

        // Starter: Core features only
        $starterFeatures = ['reservations', 'menu', 'staff'];
        foreach ($allFeatures as $feature) {
            $isIncluded = in_array($feature->feature_key, $starterFeatures);
            $starter->features()->syncWithoutDetaching([
                $feature->id => [
                    'is_included' => $isIncluded,
                    'limit_value' => $isIncluded && $feature->feature_key === 'staff' ? 5 : null,
                ]
            ]);
        }

        // Growth: Starter + Marketing + Analytics + Omnichannel + Investment + Rooms
        $growthFeatures = array_merge($starterFeatures, ['marketing', 'analytics', 'messaging', 'omnichannel_reservations', 'investment_opportunities', 'rooms', 'website_builder', 'finance', 'staff_payouts']);
        foreach ($allFeatures as $feature) {
            $isIncluded = in_array($feature->feature_key, $growthFeatures);
            $growth->features()->syncWithoutDetaching([
                $feature->id => [
                    'is_included' => $isIncluded,
                    'limit_value' => $isIncluded && $feature->feature_key === 'staff' ? 15 : null,
                ]
            ]);
        }

        // Pro: Everything
        foreach ($allFeatures as $feature) {
            $pro->features()->syncWithoutDetaching([
                $feature->id => [
                    'is_included' => true,
                    'limit_value' => $feature->feature_key === 'staff' ? 50 : null,
                ]
            ]);
        }

        // Enterprise: Unlimited
        foreach ($allFeatures as $feature) {
            $enterprise->features()->syncWithoutDetaching([
                $feature->id => [
                    'is_included' => true,
                    'limit_value' => null,
                ]
            ]);
        }
    }
}
