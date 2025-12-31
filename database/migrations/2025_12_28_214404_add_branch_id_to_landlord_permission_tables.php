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
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'branch_id';

        // Add branch_id to landlord roles table
        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->unsignedBigInteger($teamKey)->nullable()->after('id');
                $table->index($teamKey);
            }
        });

        // Add branch_id to landlord model_has_permissions table
        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->unsignedBigInteger($teamKey)->nullable(); // Nullable for landlord
                $table->index($teamKey);
            }
        });

        // Add branch_id to landlord model_has_roles table
        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->unsignedBigInteger($teamKey)->nullable(); // Nullable for landlord
                $table->index($teamKey);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'branch_id';

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });
    }
};
