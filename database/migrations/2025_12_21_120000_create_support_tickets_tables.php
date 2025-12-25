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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticketable_id');
            $table->string('ticketable_type');
            $table->string('tenant_id')->nullable();
            $table->string('subject');
            $table->string('status')->default('open'); // open, closed
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('code')->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['ticketable_id', 'ticketable_type']);
            $table->index('tenant_id');
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id'); // sender id
            $table->string('user_type'); // sender type
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
    }
};
