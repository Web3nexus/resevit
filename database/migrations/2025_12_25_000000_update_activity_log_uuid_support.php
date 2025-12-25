<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Change columns to string to support UUIDs (and integers as strings)
            $table->string('subject_id', 36)->change();
            $table->string('causer_id', 36)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Reverting might be impossible if UUIDs exist, but technically we'd go back to bigint
            // This down method is risky if data exists.
            $table->unsignedBigInteger('subject_id')->change();
            $table->unsignedBigInteger('causer_id')->nullable()->change();
        });
    }
};
