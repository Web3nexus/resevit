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
            if (!Schema::hasColumn('users', 'currency')) {
                $table->string('currency')->default('USD')->after('newsletter_subscribed');
            }
            if (!Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->default('UTC')->after('currency');
            }
        });

        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'currency')) {
                $table->string('currency')->default('USD')->after('plan_id');
            }
            if (!Schema::hasColumn('tenants', 'timezone')) {
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
            $table->dropColumn(['currency', 'timezone']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['currency', 'timezone']);
        });
    }
};
