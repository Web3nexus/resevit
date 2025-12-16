<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmedNotification extends Notification
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
            ->subject("Reservation Confirmed - {$restaurantName}")
            ->greeting("Great news, {$this->reservation->guest_name}!")
            ->line("Your reservation at {$restaurantName} has been **confirmed**.")
            ->line("**Reservation Details:**")
            ->line("Date & Time: {$this->reservation->reservation_time->format('l, F j, Y \\a\\t g:i A')}")
            ->line("Party Size: {$this->reservation->party_size} " . str('person')->plural($this->reservation->party_size))
            ->when($this->reservation->table, function ($mail) {
                return $mail->line("Table: {$this->reservation->table->name}");
            })
            ->line("Confirmation Code: **{$this->reservation->confirmation_code}**")
            ->line('Please arrive on time. If you need to cancel or modify your reservation, please contact us as soon as possible.')
            ->line('We look forward to seeing you!')
            ->salutation("Best regards,\n{$restaurantName}");
    }
}
