<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservation_settings', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('id');
            $table->string('business_address')->nullable()->after('business_name');
            $table->string('business_phone')->nullable()->after('business_address');
            $table->string('openai_api_key')->nullable()->after('business_hours');
            $table->string('google_maps_api_key')->nullable()->after('openai_api_key');
            $table->string('facebook_pixel_id')->nullable()->after('google_maps_api_key');
            $table->string('instagram_handle')->nullable()->after('facebook_pixel_id');
        });
    }

    public function down(): void
    {
        Schema::table('reservation_settings', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'business_address',
                'business_phone',
                'openai_api_key',
                'google_maps_api_key',
                'facebook_pixel_id',
                'instagram_handle',
            ]);
        });
    }
};
