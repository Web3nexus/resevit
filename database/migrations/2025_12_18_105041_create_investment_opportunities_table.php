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
        Schema::create('investment_opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index(); // Reference to Tenant model (central DB)
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('raised_amount', 15, 2)->default(0);
            $table->decimal('min_investment', 15, 2)->default(100);
            $table->decimal('roi_percentage', 5, 2)->comment('Estimated ROI percentage');
            $table->enum('status', ['draft', 'active', 'funded', 'cancelled'])->default('draft');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_opportunities');
    }
};
