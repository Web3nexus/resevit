<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old enum constraint and add new one with suspended and terminated
        DB::statement("ALTER TABLE staff MODIFY COLUMN status ENUM('active', 'inactive', 'on_leave', 'suspended', 'terminated') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE staff MODIFY COLUMN status ENUM('active', 'inactive', 'on_leave') DEFAULT 'active'");
    }
};
