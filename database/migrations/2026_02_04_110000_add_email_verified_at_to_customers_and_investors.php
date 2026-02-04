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
        Schema::connection('landlord')->table('customers', function (Blueprint $table) {
            if (!Schema::connection('landlord')->hasColumn('customers', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->after('email')->nullable();
            }
        });

        Schema::connection('landlord')->table('investors', function (Blueprint $table) {
            // Check if table exists first as it might be a future feature
            if (Schema::connection('landlord')->hasTable('investors')) {
                if (!Schema::connection('landlord')->hasColumn('investors', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->after('email')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('customers', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });

        Schema::connection('landlord')->table('investors', function (Blueprint $table) {
            if (Schema::connection('landlord')->hasTable('investors')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
