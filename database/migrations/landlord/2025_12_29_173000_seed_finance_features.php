<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PricingFeature;
use App\Models\PricingPlan;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Finance Features
        $financeFeatures = [
            [
                'name' => 'Wallet & Finance',
                'feature_key' => 'finance',
                'category' => 'Finance',
                'description' => 'Manage business wallet and view transaction history.',
                'is_active' => true,
                'is_billable' => true,
            ],
            [
                'name' => 'Staff Payouts',
                'feature_key' => 'staff_payouts',
                'category' => 'Finance',
                'description' => 'Enable paying staff directly from wallet balance.',
                'is_active' => true,
                'is_billable' => true,
            ],
        ];

        foreach ($financeFeatures as $f) {
            $feature = PricingFeature::updateOrCreate(['feature_key' => $f['feature_key']], $f);

            // 2. Assign to Growth, Pro, and Enterprise plans by default
            $plans = PricingPlan::whereIn('slug', ['growth', 'pro', 'enterprise'])->get();
            foreach ($plans as $plan) {
                $plan->features()->syncWithoutDetaching([
                    $feature->id => [
                        'is_included' => true,
                        'limit_value' => null,
                    ]
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $features = PricingFeature::whereIn('feature_key', ['finance', 'staff_payouts'])->get();
        foreach ($features as $feature) {
            $feature->plans()->detach();
            $feature->delete();
        }
    }
};
