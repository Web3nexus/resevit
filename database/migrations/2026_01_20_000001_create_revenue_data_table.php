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
        Schema::create('revenue_data', function (Blueprint $table) {
            $table->id();
            $table->date('month')->unique();
            $table->decimal('monthly_recurring_revenue', 15, 2)->default(0.00);
            $table->decimal('annual_recurring_revenue', 15, 2)->default(0.00);
            $table->decimal('total_revenue', 15, 2)->default(0.00);
            $table->decimal('churn_rate', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_data');
    }
};
