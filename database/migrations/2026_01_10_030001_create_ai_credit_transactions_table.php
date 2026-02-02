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
        Schema::connection('landlord')->create('ai_credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->decimal('amount', 15, 6); // Number of credits (can be negative for deduction)
            $table->string('type'); // 'debit' (usage) or 'credit' (allocation/purchase)
            $table->string('description')->nullable();
            $table->string('provider')->nullable(); // 'anthropic', 'openai', etc.
            $table->string('model')->nullable(); // 'claude-3-sonnet', 'gpt-4o', etc.
            $table->integer('tokens_input')->default(0);
            $table->integer('tokens_output')->default(0);
            $table->decimal('actual_cost', 15, 8)->default(0); // The $ cost to us from the provider
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('ai_credit_transactions');
    }
};
