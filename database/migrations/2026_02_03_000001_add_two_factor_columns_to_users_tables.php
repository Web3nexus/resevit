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
        $tables = ['customers', 'investors', 'influencers'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'two_factor_secret')) {
                        $table->text('two_factor_secret')->nullable()->after('password');
                    }
                    if (!Schema::hasColumn($tableName, 'two_factor_recovery_codes')) {
                        $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
                    }
                    if (!Schema::hasColumn($tableName, 'two_factor_confirmed_at')) {
                        $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['customers', 'investors', 'influencers'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn([
                        'two_factor_secret',
                        'two_factor_recovery_codes',
                        'two_factor_confirmed_at',
                    ]);
                });
            }
        }
    }
};
