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
        Schema::connection('landlord')->create('storage_settings', function (Blueprint $table) {
            $table->id();
            $table->string('active_disk')->default('public'); // public, s3, r2
            $table->string('cdn_url')->nullable();

            // S3 / Cloudflare R2 Credentials
            $table->string('s3_key')->nullable();
            $table->text('s3_secret')->nullable();
            $table->string('s3_region')->nullable();
            $table->string('s3_bucket')->nullable();
            $table->string('s3_endpoint')->nullable();

            // Cloudflare API features
            $table->string('cloudflare_api_token')->nullable();
            $table->string('cloudflare_zone_id')->nullable();
            $table->string('cloudflare_account_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_settings');
    }
};
