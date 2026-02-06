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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile')->nullable()->after('referral_code');
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('mobile');
            }
            if (!Schema::hasColumn('users', 'locale')) {
                $table->string('locale')->default('en')->after('country');
            }
            if (!Schema::hasColumn('users', 'currency')) {
                $table->string('currency')->default('USD')->after('locale');
            }
            if (!Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->default('UTC')->after('currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'mobile', 'country', 'locale', 'currency', 'timezone']);
        });
    }
};
