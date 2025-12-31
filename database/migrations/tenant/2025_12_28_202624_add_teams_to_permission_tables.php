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

        $rolePivotKey = $columnNames['role_pivot_key'] ?? 'role_id';
        $permissionPivotKey = $columnNames['permission_pivot_key'] ?? 'permission_id';

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        // 1. Update roles table
        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->unsignedBigInteger($teamKey)->nullable()->after('id');
                $table->index($teamKey);

                $table->dropUnique(['name', 'guard_name']);
                $table->unique([$teamKey, 'name', 'guard_name']);
            }
        });

        // 2. Update model_has_permissions
        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey, $columnNames, $tableNames, $permissionPivotKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->unsignedBigInteger($teamKey)->after($permissionPivotKey);
                $table->index($teamKey);

                // Drop foreign key first
                $table->dropForeign('model_has_permissions_permission_id_foreign');

                $table->dropPrimary();
                $table->primary([$teamKey, $permissionPivotKey, $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_permission_model_type_primary');

                // Re-add foreign key
                $table->foreign($permissionPivotKey)
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');
            }
        });

        // 3. Update model_has_roles
        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey, $columnNames, $tableNames, $rolePivotKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->unsignedBigInteger($teamKey)->after($rolePivotKey);
                $table->index($teamKey);

                // Drop foreign key first
                $table->dropForeign('model_has_roles_role_id_foreign');

                $table->dropPrimary();
                $table->primary([$teamKey, $rolePivotKey, $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_role_model_type_primary');

                // Re-add foreign key
                $table->foreign($rolePivotKey)
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
