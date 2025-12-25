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
            $table->enum('type', ['investment', 'crowdfunding'])->default('investment')->after('tenant_id');
            $table->enum('reward_type', ['roi', 'equity', 'perks', 'other'])->default('roi')->after('roi_percentage');
            $table->string('investment_round')->nullable()->after('reward_type')->comment('e.g. Seed, Series A, Community Round');
            $table->enum('validation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_opportunities', function (Blueprint $table) {
            $table->dropColumn(['type', 'reward_type', 'investment_round', 'validation_status']);
        });
    }
};
