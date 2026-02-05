<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('position');
            }
            if (!Schema::hasColumn('staff', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'address']);
        });
    }
};
