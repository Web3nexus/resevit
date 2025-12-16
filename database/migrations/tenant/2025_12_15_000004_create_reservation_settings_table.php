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
        Schema::create('reservation_settings', function (Blueprint $table) {
            $table->id();

            // Auto-confirmation settings
            $table->boolean('auto_confirm_enabled')->default(false);
            $table->integer('auto_confirm_hours_threshold')->default(24); // Auto-confirm if booking is X hours in advance
            $table->boolean('auto_confirm_capacity_match')->default(true); // Auto-confirm if table capacity matches party size

            // Default settings
            $table->integer('default_duration_minutes')->default(120); // Default 2 hours
            $table->integer('max_advance_booking_days')->default(90); // Max 90 days in advance
            $table->integer('min_advance_booking_hours')->default(2); // Min 2 hours in advance

            // Business hours (stored as JSON for flexibility)
            // Format: {"monday": {"open": "09:00", "close": "22:00", "closed": false}, ...}
            $table->json('business_hours')->nullable();

            // Notification settings
            $table->boolean('send_confirmation_email')->default(true);
            $table->boolean('send_reminder_24h')->default(true);
            $table->boolean('send_reminder_2h')->default(true);
            $table->boolean('notify_staff_new_reservation')->default(true);

            // Guest booking settings
            $table->boolean('allow_guest_bookings')->default(true); // Allow bookings without customer login
            $table->boolean('require_email_verification')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_settings');
    }
};
