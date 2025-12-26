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
        Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
            $table->integer('max_businesses')->default(1)->after('price_yearly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn('max_businesses');
        });
    }
};
