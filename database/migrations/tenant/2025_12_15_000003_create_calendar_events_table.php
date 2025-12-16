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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('event_type', ['reservation', 'appointment', 'personal', 'time_off'])
                ->default('appointment');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('all_day')->default(false);
            $table->foreignId('reservation_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('color', 7)->nullable(); // Hex color code
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['start_time', 'end_time']);
            $table->index('event_type');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
