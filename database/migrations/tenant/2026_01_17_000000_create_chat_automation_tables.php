<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_flows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('trigger_type'); // keyword, button, welcome_message, etc.
            $table->json('steps'); // The sequence of messages/actions
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('automation_triggers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_flow_id')->constrained('automation_flows')->cascadeOnDelete();
            $table->string('trigger_key'); // e.g., 'keyword'
            $table->string('trigger_value'); // e.g., 'book', 'reservation'
            $table->timestamps();

            $table->index(['trigger_key', 'trigger_value']);
        });

        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->foreignId('automation_flow_id')->constrained('automation_flows')->cascadeOnDelete();
            $table->integer('step_index')->default(0);
            $table->string('status'); // started, in_progress, completed, failed
            $table->json('metadata')->nullable(); // Any runtime data needed for the flow
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_logs');
        Schema::dropIfExists('automation_triggers');
        Schema::dropIfExists('automation_flows');
    }
};
