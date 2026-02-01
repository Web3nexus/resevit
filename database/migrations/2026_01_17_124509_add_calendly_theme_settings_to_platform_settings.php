<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->json('calendly_theme_settings')->nullable()->after('plugin_settings');
        });
    }

    public function down(): void
    {
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->dropColumn('calendly_theme_settings');
        });
    }
};
