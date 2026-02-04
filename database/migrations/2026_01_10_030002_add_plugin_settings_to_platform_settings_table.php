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
        if (!Schema::connection('landlord')->hasColumn('platform_settings', 'plugin_settings')) {
            Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
                $table->json('plugin_settings')->nullable()->after('stripe_settings');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->dropColumn('plugin_settings');
        });
    }
};
