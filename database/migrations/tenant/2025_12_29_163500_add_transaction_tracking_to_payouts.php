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
        Schema::table('staff_payouts', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->index(); // Landlord transaction ID
            $table->timestamp('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_payouts', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'paid_at']);
        });
    }
};
