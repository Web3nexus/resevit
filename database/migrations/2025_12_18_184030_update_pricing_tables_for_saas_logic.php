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
            $table->string('stripe_id')->nullable()->after('slug');
            $table->integer('trial_days')->default(7)->after('price_yearly');
            $table->boolean('is_trial_available')->default(true)->after('trial_days');
            $table->boolean('is_free')->default(false)->after('is_trial_available');
        });

        Schema::table('pricing_features', function (Blueprint $table) {
            $table->string('feature_key')->unique()->nullable()->after('name');
            $table->string('category')->nullable()->after('description');
            $table->boolean('is_billable')->default(true)->after('is_active');
        });

        Schema::table('pricing_plan_feature', function (Blueprint $table) {
            $table->integer('limit_value')->nullable()->after('value');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->timestamp('trial_ends_at')->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('trial_ends_at');
        });

        Schema::table('pricing_plan_feature', function (Blueprint $table) {
            $table->dropColumn('limit_value');
        });

        Schema::table('pricing_features', function (Blueprint $table) {
            $table->dropColumn(['feature_key', 'category', 'is_billable']);
        });

        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn(['stripe_id', 'trial_days', 'is_trial_available', 'is_free']);
        });
    }
};
