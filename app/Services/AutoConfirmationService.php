<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationSetting;
use App\Models\Table;
use Carbon\Carbon;

class AutoConfirmationService
{
    /**
     * Evaluate if a reservation should be auto-confirmed based on configured rules.
     */
    public function shouldAutoConfirm(Reservation $reservation): bool
    {
        $settings = ReservationSetting::getInstance();

        // Check if auto-confirmation is enabled
        if (!$settings->auto_confirm_enabled) {
            return false;
        }

        // Check if reservation is already confirmed
        if ($reservation->isConfirmed()) {
            return false;
        }

        // Run all validation rules
        $rules = [
            $this->checkTimeThreshold($reservation, $settings),
            $this->checkBusinessHours($reservation, $settings),
            $this->checkCapacityMatch($reservation, $settings),
            $this->checkAdvanceBookingLimits($reservation, $settings),
        ];

        // All rules must pass for auto-confirmation
        return !in_array(false, $rules, true);
    }

    /**
     * Auto-confirm a reservation if rules are met.
     */
    public function autoConfirmIfEligible(Reservation $reservation): bool
    {
        if ($this->shouldAutoConfirm($reservation)) {
            return $this->confirmReservation($reservation);
        }

        return false;
    }

    /**
     * Manually confirm a reservation.
     */
    public function confirmReservation(Reservation $reservation): bool
    {
        $reservation->status = 'confirmed';
        $reservation->confirmed_at = now();

        // Generate confirmation code if not exists
        if (!$reservation->confirmation_code) {
            $reservation->confirmation_code = Reservation::generateConfirmationCode();
        }

        return $reservation->save();
    }

    /**
     * Check if reservation meets time threshold requirement.
     * Auto-confirm if booking is made X hours in advance.
     */
    protected function checkTimeThreshold(Reservation $reservation, ReservationSetting $settings): bool
    {
        $hoursInAdvance = now()->diffInHours($reservation->reservation_time, false);
        return $hoursInAdvance >= $settings->auto_confirm_hours_threshold;
    }

    /**
     * Check if reservation is within business hours.
     */
    protected function checkBusinessHours(Reservation $reservation, ReservationSetting $settings): bool
    {
        // Check if reservation start time is within business hours
        if (!$settings->isWithinBusinessHours($reservation->reservation_time)) {
            return false;
        }

        // Check if reservation end time is within business hours
        $endTime = $reservation->end_time;
        if (!$settings->isWithinBusinessHours($endTime)) {
            return false;
        }

        return true;
    }

    /**
     * Check if table capacity matches party size (if enabled and table assigned).
     */
    protected function checkCapacityMatch(Reservation $reservation, ReservationSetting $settings): bool
    {
        // Skip if capacity match check is disabled
        if (!$settings->auto_confirm_capacity_match) {
            return true;
        }

        // Skip if no table assigned yet
        if (!$reservation->table_id) {
            return true; // Allow auto-confirm even without table assignment
        }

        $table = $reservation->table;
        if (!$table) {
            return true;
        }

        // Check if table capacity is appropriate for party size
        // Allow some flexibility: table capacity should be >= party size and <= party size + 2
        return $table->capacity >= $reservation->party_size &&
            $table->capacity <= ($reservation->party_size + 2);
    }

    /**
     * Check if reservation meets advance booking limits.
     */
    protected function checkAdvanceBookingLimits(Reservation $reservation, ReservationSetting $settings): bool
    {
        $hoursInAdvance = now()->diffInHours($reservation->reservation_time, false);
        $daysInAdvance = now()->diffInDays($reservation->reservation_time, false);

        // Check minimum advance booking
        if ($hoursInAdvance < $settings->min_advance_booking_hours) {
            return false;
        }

        // Check maximum advance booking
        if ($daysInAdvance > $settings->max_advance_booking_days) {
            return false;
        }

        return true;
    }

    /**
     * Get detailed auto-confirmation status for a reservation.
     * Useful for debugging or showing why a reservation wasn't auto-confirmed.
     */
    public function getAutoConfirmationStatus(Reservation $reservation): array
    {
        $settings = ReservationSetting::getInstance();

        return [
            'enabled' => $settings->auto_confirm_enabled,
            'already_confirmed' => $reservation->isConfirmed(),
            'time_threshold_met' => $this->checkTimeThreshold($reservation, $settings),
            'within_business_hours' => $this->checkBusinessHours($reservation, $settings),
            'capacity_match' => $this->checkCapacityMatch($reservation, $settings),
            'advance_booking_valid' => $this->checkAdvanceBookingLimits($reservation, $settings),
            'should_auto_confirm' => $this->shouldAutoConfirm($reservation),
        ];
    }
}
