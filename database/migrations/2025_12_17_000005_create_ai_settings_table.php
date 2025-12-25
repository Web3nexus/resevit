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
        Schema::connection('landlord')->create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // openai, anthropic
            $table->string('api_key')->nullable();
            $table->string('chat_model')->default('gpt-4o-mini'); // GPT-4o-mini, GPT-4o
            $table->string('premium_model')->default('gpt-4o');
            $table->string('image_model')->default('dall-e-3');
            $table->string('embedding_model')->default('text-embedding-3-large');
            $table->string('code_model')->default('claude-3-5-sonnet-20241022'); // Claude for code
            $table->boolean('is_active')->default(false);
            $table->json('rate_limits')->nullable(); // Store rate limit configs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('ai_settings');
    }
};
