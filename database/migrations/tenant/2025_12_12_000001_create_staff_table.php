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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('position'); // manager, accountant, staff, cashier, waiter
            $table->string('phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->date('hire_date');
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->json('availability')->nullable(); // Weekly schedule
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
