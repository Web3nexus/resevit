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
        if (!Schema::connection('landlord')->hasColumn('tenants', 'ai_credits')) {
            Schema::connection('landlord')->table('tenants', function (Blueprint $table) {
                $table->decimal('ai_credits', 15, 6)->default(0)->after('status');
            });
        }

        if (!Schema::connection('landlord')->hasColumn('pricing_plans', 'monthly_ai_credits')) {
            Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
                $table->decimal('monthly_ai_credits', 15, 6)->default(0)->after('price_yearly');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('tenants', function (Blueprint $table) {
            $table->dropColumn('ai_credits');
        });

        Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn('monthly_ai_credits');
        });
    }
};
