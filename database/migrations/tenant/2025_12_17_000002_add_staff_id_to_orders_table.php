<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'staff_id')) {
            Schema::table('orders', function (Blueprint $table) {
                // Assuming 'staff' table exists. nullable in case order is online or no waiter.
                $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete(); 
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'staff_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['staff_id']);
                $table->dropColumn('staff_id');
            });
        }
    }
};
