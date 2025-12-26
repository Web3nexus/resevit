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
        // 1. Add wallet balance to users
        Schema::connection('landlord')->table('users', function (Blueprint $table) {
            $table->decimal('wallet_balance', 15, 2)->default(0)->after('locale');
        });

        // 2. Add promotion settings to platform settings
        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->json('promotion_settings')->nullable()->after('error_pages');
        });

        // 3. Create transactions table
        Schema::connection('landlord')->create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->nullableUuidMorphs('transactionable'); // Link to Tenant or other models
            $table->decimal('amount', 15, 2);
            $table->string('type'); // 'deposit', 'withdrawal', 'payment'
            $table->string('status')->default('completed'); // 'pending', 'completed', 'failed'
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('transactions');

        Schema::connection('landlord')->table('platform_settings', function (Blueprint $table) {
            $table->dropColumn('promotion_settings');
        });

        Schema::connection('landlord')->table('users', function (Blueprint $table) {
            $table->dropColumn('wallet_balance');
        });
    }
};
