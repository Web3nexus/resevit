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
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->json('landing_settings')->nullable()->after('promotion_settings');
            $table->json('legal_settings')->nullable()->after('landing_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->dropColumn(['landing_settings', 'legal_settings']);
        });
    }
};
