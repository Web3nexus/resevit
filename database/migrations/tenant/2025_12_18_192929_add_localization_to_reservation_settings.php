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
        Schema::table('reservation_settings', function (Blueprint $table) {
            $table->string('currency')->default('USD')->after('business_hours');
            $table->string('timezone')->default('UTC')->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_settings', function (Blueprint $table) {
            $table->dropColumn(['currency', 'timezone']);
        });
    }
};
