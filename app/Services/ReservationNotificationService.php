<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationSetting;
use App\Models\TenantUser;
use App\Notifications\ReservationCreatedNotification;
use App\Notifications\ReservationConfirmedNotification;
use App\Notifications\ReservationCancelledNotification;
use App\Notifications\ReservationReminderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;

class ReservationNotificationService
{
    /**
     * Send notification when a new reservation is created.
     */
    public function sendCreatedNotification(Reservation $reservation): void
    {
        $settings = ReservationSetting::getInstance();

        // Send confirmation email to guest
        if ($settings->send_confirmation_email && $reservation->guest_email) {
            $this->sendGuestEmail($reservation, new ReservationCreatedNotification($reservation));
        }

        // Notify staff about new reservation
        if ($settings->notify_staff_new_reservation) {
            $this->notifyStaff($reservation, 'new_reservation');
        }
    }

    /**
     * Send notification when a reservation is confirmed.
     */
    public function sendConfirmedNotification(Reservation $reservation): void
    {
        $settings = ReservationSetting::getInstance();

        // Send confirmation email to guest
        if ($settings->send_confirmation_email && $reservation->guest_email) {
            $this->sendGuestEmail($reservation, new ReservationConfirmedNotification($reservation));
        }
    }

    /**
     * Send notification when a reservation is cancelled.
     */
    public function sendCancelledNotification(Reservation $reservation): void
    {
        // Always send cancellation notification
        if ($reservation->guest_email) {
            $this->sendGuestEmail($reservation, new ReservationCancelledNotification($reservation));
        }

        // Notify staff about cancellation
        $this->notifyStaff($reservation, 'cancelled_reservation');
    }

    /**
     * Send reminder notification before reservation time.
     */
    public function sendReminderNotification(Reservation $reservation, int $hoursBeforeReservation): void
    {
        if ($reservation->guest_email) {
            $this->sendGuestEmail(
                $reservation,
                new ReservationReminderNotification($reservation, $hoursBeforeReservation)
            );

            // Mark reminder as sent
            $reservation->reminder_sent_at = now();
            $reservation->save();
        }
    }

    /**
     * Send email to guest.
     */
    protected function sendGuestEmail(Reservation $reservation, $notification): void
    {
        try {
            // Create a temporary notifiable object with guest email
            $notifiable = new class ($reservation->guest_email) {
                public function __construct(public string $email)
                {
                }

                public function routeNotificationForMail()
                {
                    return $this->email;
                }
            };

            $notifiable->notify($notification);
        } catch (\Exception $e) {
            \Log::error('Failed to send reservation email', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify staff members about reservation events.
     */
    protected function notifyStaff(Reservation $reservation, string $eventType): void
    {
        try {
            // Get business owners and managers (roles that should be notified)
            $staffToNotify = TenantUser::whereHas('roles', function ($query) {
                $query->whereIn('name', ['business_owner', 'manager']);
            })->get();

            if ($staffToNotify->isEmpty()) {
                return;
            }

            // Send database notification
            $title = match ($eventType) {
                'new_reservation' => 'New Reservation',
                'cancelled_reservation' => 'Reservation Cancelled',
                default => 'Reservation Update',
            };

            $body = match ($eventType) {
                'new_reservation' => "New reservation for {$reservation->guest_name} on {$reservation->reservation_time->format('M d, Y g:i A')}",
                'cancelled_reservation' => "Reservation for {$reservation->guest_name} on {$reservation->reservation_time->format('M d, Y g:i A')} has been cancelled",
                default => "Reservation updated for {$reservation->guest_name}",
            };

            foreach ($staffToNotify as $staff) {
                \Filament\Notifications\Notification::make()
                    ->title($title)
                    ->body($body)
                    ->icon('heroicon-o-calendar-days')
                    ->iconColor($eventType === 'new_reservation' ? 'success' : 'warning')
                    ->sendToDatabase($staff);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to notify staff', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Process reminder notifications for upcoming reservations.
     * This should be called by a scheduled job.
     */
    public function processReminders(): void
    {
        $settings = ReservationSetting::getInstance();

        // Process 24-hour reminders
        if ($settings->send_reminder_24h) {
            $this->processReminderBatch(24);
        }

        // Process 2-hour reminders
        if ($settings->send_reminder_2h) {
            $this->processReminderBatch(2);
        }
    }

    /**
     * Process a batch of reminders for a specific time window.
     */
    protected function processReminderBatch(int $hoursBeforeReservation): void
    {
        $reservations = Reservation::whereIn('status', ['confirmed', 'pending'])
            ->whereNull('reminder_sent_at')
            ->get()
            ->filter(fn($r) => $r->needsReminder($hoursBeforeReservation));

        foreach ($reservations as $reservation) {
            $this->sendReminderNotification($reservation, $hoursBeforeReservation);
        }
    }
}
