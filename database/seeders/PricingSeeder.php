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
            'trial_days' => 7,
            'stripe_id' => 'price_STARTER_PLACEHOLDER', // Replace with Stripe Price ID
            'order' => 1,
        ]);

        $growth = \App\Models\PricingPlan::updateOrCreate(['slug' => 'growth'], [
            'name' => 'Growth',
            'description' => 'Scale your restaurant with marketing and AI.',
            'price_monthly' => 79.00,
            'price_yearly' => 790.00,
            'stripe_id' => 'price_GROWTH_PLACEHOLDER', // Replace with Stripe Price ID
            'order' => 2,
        ]);

        $pro = \App\Models\PricingPlan::updateOrCreate(['slug' => 'pro'], [
            'name' => 'Pro',
            'description' => 'For busy restaurants that need advanced POS and analytics.',
            'price_monthly' => 149.00,
            'price_yearly' => 1490.00,
            'stripe_id' => 'price_PRO_PLACEHOLDER', // Replace with Stripe Price ID
            'order' => 3,
        ]);

        $enterprise = \App\Models\PricingPlan::updateOrCreate(['slug' => 'enterprise'], [
            'name' => 'Enterprise',
            'description' => 'Custom solutions for high-volume establishments.',
            'price_monthly' => 499.00,
            'price_yearly' => 4990.00,
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
        $growthFeatures = array_merge($starterFeatures, ['marketing', 'analytics', 'messaging', 'omnichannel_reservations', 'investment_opportunities', 'rooms']);
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
