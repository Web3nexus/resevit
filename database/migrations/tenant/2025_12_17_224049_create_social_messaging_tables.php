<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            // No tenant_id needed here as this is inside the tenant DB context
            $table->string('platform'); // whatsapp, facebook, instagram
            $table->string('external_account_id')->index(); // e.g. Phone number ID, Page ID
            $table->text('credentials'); // JSON encrypted
            $table->string('name')->nullable(); // Friendly name
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['platform', 'external_account_id']);
        });

        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('source'); // whatsapp, facebook, instagram
            $table->string('external_chat_id')->index(); // Phone number or user PSID
            $table->string('customer_name')->nullable();
            $table->string('customer_handle')->nullable(); // Phone number or username
            $table->string('status')->default('open'); // open, closed, archived
            $table->timestamp('last_message_at')->useCurrent();
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamps();

            // Index for faster lookups
            $table->index(['source', 'external_chat_id']);
            $table->index(['status', 'last_message_at']);
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->enum('direction', ['inbound', 'outbound']);
            $table->text('content')->nullable();
            $table->string('external_message_id')->nullable()->index();
            $table->json('metadata')->nullable(); // Raw payload, attachments info, etc.
            $table->string('status')->default('sent'); // sent, delivered, read, failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chats');
        Schema::dropIfExists('social_accounts');
    }
};
