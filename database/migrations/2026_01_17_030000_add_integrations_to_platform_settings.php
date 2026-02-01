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
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            if (! Schema::connection('landlord')->hasColumn('platform_settings', 'stripe_settings')) {
                $table->text('stripe_settings')->nullable()->after('promotion_settings');
            }
            if (! Schema::connection('landlord')->hasColumn('platform_settings', 'plugin_settings')) {
                $table->json('plugin_settings')->nullable()->after('stripe_settings');
            }
            if (! Schema::connection('landlord')->hasColumn('platform_settings', 'stripe_mode')) {
                $table->string('stripe_mode')->default('test')->after('stripe_settings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->dropColumn(['stripe_settings', 'plugin_settings', 'stripe_mode']);
        });
    }
};
