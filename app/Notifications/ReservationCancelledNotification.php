<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelledNotification extends Notification
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
            ->subject("Reservation Cancelled - {$restaurantName}")
            ->greeting("Hello {$this->reservation->guest_name},")
            ->line("Your reservation at {$restaurantName} has been cancelled.")
            ->line("**Cancelled Reservation:**")
            ->line("Date & Time: {$this->reservation->reservation_time->format('l, F j, Y \\a\\t g:i A')}")
            ->line("Party Size: {$this->reservation->party_size} " . str('person')->plural($this->reservation->party_size))
            ->when($this->reservation->confirmation_code, function ($mail) {
                return $mail->line("Confirmation Code: {$this->reservation->confirmation_code}");
            })
            ->line('If you did not request this cancellation or would like to make a new reservation, please contact us.')
            ->line('We hope to serve you in the future!')
            ->salutation("Best regards,\n{$restaurantName}");
    }
}
