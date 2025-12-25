<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::connection('landlord')->hasTable('customer_conversations')) {
            Schema::connection('landlord')->create('customer_conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
                $table->string('tenant_id'); // UUID link to tenants table
                $table->string('status')->default('open'); // open, closed
                $table->timestamps();
            });
        }

        if (!Schema::connection('landlord')->hasTable('customer_messages')) {
            Schema::connection('landlord')->create('customer_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_conversation_id')->constrained('customer_conversations')->cascadeOnDelete();
                $table->string('sender_type'); // 'customer' or 'staff' (or 'user')
                $table->unsignedBigInteger('sender_id');
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('customer_messages');
        Schema::connection('landlord')->dropIfExists('customer_conversations');
    }
};
