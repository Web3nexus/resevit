<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
            $table->string('stripe_yearly_id')->nullable()->after('stripe_id');
        });
    }

    public function down(): void
    {
        Schema::connection('landlord')->table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn('stripe_yearly_id');
        });
    }
};
