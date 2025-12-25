<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staff_conversations')) {
            Schema::create('staff_conversations', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('private'); // private, group
                $table->string('name')->nullable(); // For group chats
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('staff_conversation_participants')) {
            Schema::create('staff_conversation_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('staff_conversation_id')->constrained()->cascadeOnDelete();
                $table->unsignedBigInteger('user_id'); // Tenant User ID (from users table in tenant DB usually, or global ID? Staff operate in Tenant DB so likely tenant user id)
                // Wait, users are in LANDLORD DB but referenced in tenant DB? 
                // Feature 6 used `staff_id` which links to `staff` table. `staff` table has `user_id` which might be global?
                // Actually, `users` table exists in Tenant DB (synced or independent?). 
                // Let's assume we link to `users` table which contains the staff's login record. 
                // In standard hybrid setup, Users are often global.
                // But `Staff` model belongsTo `TenantUser`. Let's check `Staff` model again.
                // Model `Staff` has `user()` belonging to `TenantUser::class`. 
                // Let's assume `user_id` refers to the `users` table in the same DB (likely the Tenant DB's user copy or reference).
                // Ideally we link to `staff` ID for staff chat? No, `user_id` allows Admin participating too.
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('staff_messages')) {
            Schema::create('staff_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('staff_conversation_id')->constrained()->cascadeOnDelete();
                $table->unsignedBigInteger('sender_id'); // User ID
                $table->text('message');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_messages');
        Schema::dropIfExists('staff_conversation_participants');
        Schema::dropIfExists('staff_conversations');
    }
};
