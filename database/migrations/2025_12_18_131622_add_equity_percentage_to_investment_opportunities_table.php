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
        Schema::table('investment_opportunities', function (Blueprint $table) {
            $table->decimal('equity_percentage', 5, 2)->nullable()->after('roi_percentage');
            $table->text('reward_details')->nullable()->after('reward_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_opportunities', function (Blueprint $table) {
            $table->dropColumn(['equity_percentage', 'reward_details']);
        });
    }
};
