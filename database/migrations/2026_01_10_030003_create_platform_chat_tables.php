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
        Schema::connection('landlord')->create('platform_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('session_id')->nullable()->index();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('subject')->nullable();
            $table->string('status')->default('open')->index(); // open, closed
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        Schema::connection('landlord')->create('platform_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_conversation_id')->constrained('platform_conversations')->onDelete('cascade');
            $table->enum('sender_type', ['admin', 'user', 'guest']);
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('body');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('platform_messages');
        Schema::connection('landlord')->dropIfExists('platform_conversations');
    }
};
