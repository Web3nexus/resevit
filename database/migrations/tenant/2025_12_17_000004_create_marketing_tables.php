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
        Schema::create('marketing_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // email, sms, social
            $table->string('subject')->nullable(); // For email
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // email, sms, social
            $table->string('status')->default('draft'); // draft, scheduled, sent, failed
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('image_path')->nullable(); // For social media images
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->json('audience_filter')->nullable(); // e.g. ['vip', 'new_users']
            $table->json('stats')->nullable(); // e.g. {'recipients': 100, 'opened': 20}
            $table->foreignId('created_by')->nullable(); // User ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_campaigns');
        Schema::dropIfExists('marketing_templates');
    }
};
