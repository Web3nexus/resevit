<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationSetting extends Model
{
    protected $fillable = [
        'business_name',
        'business_address',
        'business_phone',
        'openai_api_key',
        'google_maps_api_key',
        'facebook_pixel_id',
        'instagram_handle',
        'auto_confirm_enabled',
        'auto_confirm_hours_threshold',
        'auto_confirm_capacity_match',
        'default_duration_minutes',
        'max_advance_booking_days',
        'min_advance_booking_hours',
        'business_hours',
        'send_confirmation_email',
        'send_reminder_24h',
        'send_reminder_2h',
        'notify_staff_new_reservation',
        'allow_guest_bookings',
        'require_email_verification',
    ];

    protected $casts = [
        'auto_confirm_enabled' => 'boolean',
        'auto_confirm_hours_threshold' => 'integer',
        'auto_confirm_capacity_match' => 'boolean',
        'default_duration_minutes' => 'integer',
        'max_advance_booking_days' => 'integer',
        'min_advance_booking_hours' => 'integer',
        'business_hours' => 'array',
        'send_confirmation_email' => 'boolean',
        'send_reminder_24h' => 'boolean',
        'send_reminder_2h' => 'boolean',
        'notify_staff_new_reservation' => 'boolean',
        'allow_guest_bookings' => 'boolean',
        'require_email_verification' => 'boolean',
    ];

    /**
     * Get the singleton instance of reservation settings.
     * Creates default settings if none exist.
     */
    public static function getInstance(): self
    {
        try {
            $settings = self::first();

            if (!$settings) {
                $settings = self::create([
                    'auto_confirm_enabled' => false,
                    'auto_confirm_hours_threshold' => 24,
                    'auto_confirm_capacity_match' => true,
                    'default_duration_minutes' => 120,
                    'max_advance_booking_days' => 90,
                    'min_advance_booking_hours' => 2,
                    'business_hours' => self::getDefaultBusinessHours(),
                    'send_confirmation_email' => true,
                    'send_reminder_24h' => true,
                    'send_reminder_2h' => true,
                    'notify_staff_new_reservation' => true,
                    'allow_guest_bookings' => true,
                    'require_email_verification' => false,
                ]);
            }

            return $settings;
        } catch (\Exception $e) {
            // Table doesn't exist yet - return a new instance with defaults
            // This allows the app to work before migrations are run
            $instance = new self();
            $instance->fill([
                'auto_confirm_enabled' => false,
                'auto_confirm_hours_threshold' => 24,
                'auto_confirm_capacity_match' => true,
                'default_duration_minutes' => 120,
                'max_advance_booking_days' => 90,
                'min_advance_booking_hours' => 2,
                'business_hours' => self::getDefaultBusinessHours(),
                'send_confirmation_email' => true,
                'send_reminder_24h' => true,
                'send_reminder_2h' => true,
                'notify_staff_new_reservation' => true,
                'allow_guest_bookings' => true,
                'require_email_verification' => false,
            ]);
            return $instance;
        }
    }

    /**
     * Get default business hours (9 AM - 10 PM, all days).
     */
    protected static function getDefaultBusinessHours(): array
    {
        $defaultHours = [
            'open' => '09:00',
            'close' => '22:00',
            'closed' => false,
        ];

        return [
            'monday' => $defaultHours,
            'tuesday' => $defaultHours,
            'wednesday' => $defaultHours,
            'thursday' => $defaultHours,
            'friday' => $defaultHours,
            'saturday' => $defaultHours,
            'sunday' => $defaultHours,
        ];
    }

    /**
     * Check if a given time is within business hours.
     */
    public function isWithinBusinessHours(\Carbon\Carbon $dateTime): bool
    {
        $dayOfWeek = strtolower($dateTime->format('l'));
        $hours = $this->business_hours[$dayOfWeek] ?? null;

        if (!$hours || ($hours['closed'] ?? false)) {
            return false;
        }

        $time = $dateTime->format('H:i');
        return $time >= $hours['open'] && $time <= $hours['close'];
    }
}
