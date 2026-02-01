<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'branch_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $modelMorphKey = $columnNames['model_morph_key'] ?? 'model_id';

        // 1. model_has_roles
        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey, $pivotRole, $modelMorphKey) {
            // Drop existing primary key
            // Note: Key name might vary, but Spatie default is usually role_model_type_primary
            // However, after our recent migrations it might include the teamKey.
            $table->dropPrimary();

            // Make branch_id nullable
            $table->unsignedBigInteger($teamKey)->nullable()->change();

            // Add new primary key without branch_id
            $table->primary([$pivotRole, $modelMorphKey, 'model_type'], 'model_has_roles_role_model_type_primary');

            // Add index for branch_id if not exists
            $table->index($teamKey);
        });

        // 2. model_has_permissions
        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey, $pivotPermission, $modelMorphKey) {
            $table->dropPrimary();

            $table->unsignedBigInteger($teamKey)->nullable()->change();

            $table->primary([$pivotPermission, $modelMorphKey, 'model_type'], 'model_has_permissions_permission_model_type_primary');

            $table->index($teamKey);
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
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $modelMorphKey = $columnNames['model_morph_key'] ?? 'model_id';

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey, $pivotRole, $modelMorphKey) {
            $table->dropPrimary();
            $table->unsignedBigInteger($teamKey)->nullable(false)->change();
            $table->primary([$teamKey, $pivotRole, $modelMorphKey, 'model_type'], 'model_has_roles_role_model_type_primary');
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey, $pivotPermission, $modelMorphKey) {
            $table->dropPrimary();
            $table->unsignedBigInteger($teamKey)->nullable(false)->change();
            $table->primary([$teamKey, $pivotPermission, $modelMorphKey, 'model_type'], 'model_has_permissions_permission_model_type_primary');
        });
    }
};
