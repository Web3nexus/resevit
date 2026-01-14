<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
            // Add product IDs for both environments
            $table->string('stripe_product_id_test')->nullable()->after('stripe_yearly_id');
            $table->string('stripe_product_id_live')->nullable()->after('stripe_product_id_test');

            // Add price IDs for both environments (monthly and yearly)
            $table->string('stripe_price_id_test')->nullable()->after('stripe_product_id_live');
            $table->string('stripe_price_id_live')->nullable()->after('stripe_price_id_test');
            $table->string('stripe_yearly_price_id_test')->nullable()->after('stripe_price_id_live');
            $table->string('stripe_yearly_price_id_live')->nullable()->after('stripe_yearly_price_id_test');
        });

        // Migrate existing stripe_id and stripe_yearly_id to test environment
        $this->migrateExistingPriceIds();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_product_id_test',
                'stripe_product_id_live',
                'stripe_price_id_test',
                'stripe_price_id_live',
                'stripe_yearly_price_id_test',
                'stripe_yearly_price_id_live',
            ]);
        });
    }

    /**
     * Migrate existing price IDs to test environment fields
     */
    protected function migrateExistingPriceIds(): void
    {
        DB::connection('landlord')
            ->table('pricing_plans')
            ->whereNotNull('stripe_id')
            ->orWhereNotNull('stripe_yearly_id')
            ->get()
            ->each(function ($plan) {
                $updates = [];

                if ($plan->stripe_id) {
                    $updates['stripe_price_id_test'] = $plan->stripe_id;
                }

                if ($plan->stripe_yearly_id) {
                    $updates['stripe_yearly_price_id_test'] = $plan->stripe_yearly_id;
                }

                if (!empty($updates)) {
                    DB::connection('landlord')
                        ->table('pricing_plans')
                        ->where('id', $plan->id)
                        ->update($updates);
                }
            });
    }
};
