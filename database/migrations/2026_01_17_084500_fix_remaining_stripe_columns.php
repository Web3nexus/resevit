<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pricing_plans')) {
            Schema::table('pricing_plans', function (Blueprint $table) {
                // Add stripe_yearly_price_id_test if missing
                if (! Schema::hasColumn('pricing_plans', 'stripe_yearly_price_id_test')) {
                    $table->string('stripe_yearly_price_id_test')->nullable()->after('stripe_price_id_live');
                }

                // Add stripe_yearly_price_id_live if missing
                if (! Schema::hasColumn('pricing_plans', 'stripe_yearly_price_id_live')) {
                    $table->string('stripe_yearly_price_id_live')->nullable()->after('stripe_yearly_price_id_test');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $columns = [
                'stripe_yearly_price_id_test',
                'stripe_yearly_price_id_live',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('pricing_plans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
