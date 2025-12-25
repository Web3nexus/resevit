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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained('investment_opportunities')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('current_value', 15, 2)->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->string('contract_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
