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
        Schema::table('email_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('smtp_configuration_id')->nullable()->after('is_active');
            // Assuming foreign key constraint logic might be needed depending on DB engine/setup
            // but keeping it simple for now to avoid errors if table doesn't exist yet in this context
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('smtp_configuration_id');
        });
    }
};
