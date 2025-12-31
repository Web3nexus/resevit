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
        $tableName = config('activitylog.table_name') ?? 'activity_log';

        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'branch_id')) {
                    $table->unsignedBigInteger('branch_id')->nullable()->after('id');
                    $table->index('branch_id');
                }

                if (!Schema::hasColumn($tableName, 'batch_uuid')) {
                    $table->uuid('batch_uuid')->nullable()->after('properties');
                }

                if (!Schema::hasColumn($tableName, 'event')) {
                    $table->string('event')->nullable()->after('subject_type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op to avoid rollback issues with foreign keys
    }
};
