<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $blueprint) {
            $blueprint->json('footer_settings')->nullable()->after('supported_languages');
        });
    }

    public function down(): void
    {
        Schema::table('platform_settings', function (Blueprint $blueprint) {
            $blueprint->dropColumn('footer_settings');
        });
    }
};
