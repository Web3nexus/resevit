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
        Schema::table('tenants', function (Blueprint $table) {
            // Add business information columns if they don't exist
            if (!Schema::hasColumn('tenants', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('tenants', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
            if (!Schema::hasColumn('tenants', 'domain')) {
                $table->string('domain')->unique()->after('slug');
            }
            if (!Schema::hasColumn('tenants', 'database_name')) {
                $table->string('database_name')->unique()->nullable()->after('domain');
            }
            if (!Schema::hasColumn('tenants', 'owner_user_id')) {
                $table->unsignedBigInteger('owner_user_id')->nullable()->after('database_name');
            }
            if (!Schema::hasColumn('tenants', 'status')) {
                $table->enum('status', ['active', 'suspended', 'deleted'])->default('active')->after('owner_user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Drop the added columns in reverse order
            $columnsToDropIfExist = ['status', 'owner_user_id', 'database_name', 'domain', 'slug', 'name'];
            foreach ($columnsToDropIfExist as $column) {
                if (Schema::hasColumn('tenants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
