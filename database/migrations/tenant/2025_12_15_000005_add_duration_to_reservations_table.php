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
        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('duration_minutes')->default(120)->after('reservation_time');
            $table->timestamp('confirmed_at')->nullable()->after('status');
            $table->timestamp('reminder_sent_at')->nullable()->after('confirmed_at');
            $table->string('confirmation_code')->nullable()->unique()->after('reminder_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['duration_minutes', 'confirmed_at', 'reminder_sent_at', 'confirmation_code']);
        });
    }
};
