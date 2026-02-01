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
        Schema::table('reservation_settings', function (Blueprint $table) {
            $table->string('business_logo')->nullable()->after('business_phone');
            $table->json('social_links')->nullable()->after('business_logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_settings', function (Blueprint $table) {
            $table->dropColumn(['business_logo', 'social_links']);
        });
    }
};
