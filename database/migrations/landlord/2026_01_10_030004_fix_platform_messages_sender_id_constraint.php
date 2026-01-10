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
        Schema::connection('landlord')->table('platform_messages', function (Blueprint $table) {
            // Drop the restrictive foreign key that ties sender_id to the users table
            $table->dropForeign(['sender_id']);

            // Ensure sender_id is just a big integer that can hold either User or Admin IDs
            $table->unsignedBigInteger('sender_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->table('platform_messages', function (Blueprint $table) {
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
