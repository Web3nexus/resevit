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
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_account_id')->nullable();
            $table->boolean('stripe_onboarding_complete')->default(false);
            $table->boolean('stripe_charges_enabled')->default(false);
            $table->enum('stripe_mode', ['test', 'live'])->default('test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_account_id',
                'stripe_onboarding_complete',
                'stripe_charges_enabled',
                'stripe_mode',
            ]);
        });
    }
};
