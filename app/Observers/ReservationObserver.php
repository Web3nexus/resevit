<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Services\CalendarService;
use App\Services\AutoConfirmationService;
use App\Services\ReservationNotificationService;

class ReservationObserver
{
    public function __construct(
        protected CalendarService $calendarService,
        protected AutoConfirmationService $autoConfirmationService,
        protected ReservationNotificationService $notificationService
    ) {}

    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        // Generate confirmation code if not exists
        if (!$reservation->confirmation_code) {
            $reservation->confirmation_code = Reservation::generateConfirmationCode();
            $reservation->saveQuietly(); // Save without triggering events again
        }

        // Check and apply auto-confirmation
        $wasAutoConfirmed = $this->autoConfirmationService->autoConfirmIfEligible($reservation);

        // Sync to calendar
        $this->calendarService->syncReservationToCalendar($reservation);

        // Send notifications
        $this->notificationService->sendCreatedNotification($reservation);

        // If auto-confirmed, send confirmation notification
        if ($wasAutoConfirmed) {
            $this->notificationService->sendConfirmedNotification($reservation);
        }
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        // Only sync if the reservation time or relevant details changed
        if ($reservation->wasChanged(['reservation_time', 'guest_name', 'party_size', 'status', 'table_id', 'duration_minutes'])) {
            $this->calendarService->syncReservationToCalendar($reservation);
        }

        // If status changed to confirmed, send confirmation notification
        if ($reservation->isDirty('status') && $reservation->status === 'confirmed') {
            if (!$reservation->confirmed_at) {
                $reservation->confirmed_at = now();
                $reservation->saveQuietly();
            }
            $this->notificationService->sendConfirmedNotification($reservation);
        }

        // If status changed to cancelled, send cancellation notification
        if ($reservation->isDirty('status') && $reservation->status === 'cancelled') {
            $this->notificationService->sendCancelledNotification($reservation);
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        // Soft delete the associated calendar event
        if ($reservation->calendarEvent) {
            $reservation->calendarEvent->delete();
        }

        // Send cancellation notification if reservation was confirmed
        if ($reservation->isConfirmed()) {
            $this->notificationService->sendCancelledNotification($reservation);
        }
    }

    /**
     * Handle the Reservation "restored" event.
     */
    public function restored(Reservation $reservation): void
    {
        // Restore the calendar event if it was soft deleted
        if ($reservation->calendarEvent()->withTrashed()->exists()) {
            $reservation->calendarEvent()->withTrashed()->first()->restore();
        } else {
            // Create new calendar event if it doesn't exist
            $this->calendarService->syncReservationToCalendar($reservation);
        }
    }
}
