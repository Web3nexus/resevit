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
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // hero, features, stats, testimonials, etc.
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->json('content')->nullable(); // For flexible section data
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_section_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('icon')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_text')->nullable();
            $table->json('extra')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('company')->nullable();
            $table->text('content');
            $table->integer('rating')->default(5);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2);
            $table->string('cta_text')->default('Get Started');
            $table->string('cta_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_plan_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pricing_feature_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_included')->default(true);
            $table->string('value')->nullable(); // e.g. "Up to 5 users"
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('pricing_plan_feature');
        Schema::dropIfExists('pricing_features');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('landing_items');
        Schema::dropIfExists('landing_sections');
        Schema::dropIfExists('landing_pages');
    }
};
