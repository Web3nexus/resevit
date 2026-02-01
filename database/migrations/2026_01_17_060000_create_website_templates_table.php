<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail_path')->nullable();
            $table->json('structure_schema')->nullable(); // Defines what fields are editable (hero_text, about_image, etc.)
            $table->json('default_content')->nullable(); // Default values for the schema
            $table->boolean('is_active')->default(true);
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        Schema::create('tenant_websites', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // Foreign key to tenants table (using string id as per existing schema)
            $table->foreignId('website_template_id')->constrained()->cascadeOnDelete();

            $table->json('content')->nullable(); // The actual content specific to this tenant
            $table->json('settings')->nullable(); // Custom fonts, colors overrides

            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->unique('tenant_id'); // One website per tenant for now
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_websites');
        Schema::dropIfExists('website_templates');
    }
};
