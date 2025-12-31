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
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->decimal('yearly_discount_percentage', 5, 2)->default(0)->after('price_yearly');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('influencer_id')->nullable()->after('plan_id')->constrained('influencers')->onDelete('set null');
            $table->string('subscription_interval')->nullable()->after('influencer_id'); // monthly, yearly
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['influencer_id']);
            $table->dropColumn(['influencer_id', 'subscription_interval']);
        });

        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn('yearly_discount_percentage');
        });
    }
};
