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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();

            // Task Details
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('general'); // general, cleaning, maintenance, custom
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->dateTime('due_date')->nullable();

            // Associations
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            // Assign to a specific Staff member (who belongs to the branch)
            $table->foreignId('assigned_to_staff_id')->nullable()->constrained('staff')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['branch_id', 'status']);
            $table->index('assigned_to_staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
