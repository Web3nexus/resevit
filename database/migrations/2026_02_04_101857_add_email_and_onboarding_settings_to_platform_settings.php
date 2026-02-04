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
            if (!Schema::connection('landlord')->hasColumn('platform_settings', 'email_settings')) {
                $table->json('email_settings')->nullable()->after('legal_settings');
            }
            if (!Schema::connection('landlord')->hasColumn('platform_settings', 'onboarding_settings')) {
                $table->json('onboarding_settings')->nullable()->after('email_settings');
            }
            if (!Schema::connection('landlord')->hasColumn('platform_settings', 'logo_dark_path')) {
                $table->string('logo_dark_path')->nullable()->after('logo_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->dropColumn(['email_settings', 'onboarding_settings', 'logo_dark_path']);
        });
    }
};
