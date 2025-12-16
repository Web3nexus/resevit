<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Reservation $reservation
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $tenant = tenant();
        $restaurantName = $tenant?->name ?? 'Restaurant';

        return (new MailMessage)
            ->subject("Reservation Confirmation - {$restaurantName}")
            ->greeting("Hello {$this->reservation->guest_name}!")
            ->line("Thank you for your reservation at {$restaurantName}.")
            ->line("**Reservation Details:**")
            ->line("Date & Time: {$this->reservation->reservation_time->format('l, F j, Y \\a\\t g:i A')}")
            ->line("Party Size: {$this->reservation->party_size} " . str('person')->plural($this->reservation->party_size))
            ->line("Duration: {$this->reservation->duration_minutes} minutes")
            ->when($this->reservation->table, function ($mail) {
                return $mail->line("Table: {$this->reservation->table->name}");
            })
            ->when($this->reservation->confirmation_code, function ($mail) {
                return $mail->line("Confirmation Code: **{$this->reservation->confirmation_code}**");
            })
            ->when($this->reservation->special_requests, function ($mail) {
                return $mail->line("Special Requests: {$this->reservation->special_requests}");
            })
            ->line($this->reservation->status === 'confirmed'
                ? '✓ Your reservation is **confirmed**.'
                : 'Your reservation is pending confirmation. We will notify you once confirmed.')
            ->line('We look forward to serving you!')
            ->salutation("Best regards,\n{$restaurantName}");
    }
}
