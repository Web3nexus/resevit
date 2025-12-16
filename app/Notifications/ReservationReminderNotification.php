<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Reservation $reservation,
        public int $hoursBeforeReservation
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $tenant = tenant();
        $restaurantName = $tenant?->name ?? 'Restaurant';

        $timeframe = $this->hoursBeforeReservation === 24 ? 'tomorrow' : 'soon';

        return (new MailMessage)
            ->subject("Reservation Reminder - {$restaurantName}")
            ->greeting("Hello {$this->reservation->guest_name}!")
            ->line("This is a friendly reminder about your upcoming reservation at {$restaurantName}.")
            ->line("**Your reservation is {$timeframe}:**")
            ->line("Date & Time: {$this->reservation->reservation_time->format('l, F j, Y \\a\\t g:i A')}")
            ->line("Party Size: {$this->reservation->party_size} " . str('person')->plural($this->reservation->party_size))
            ->when($this->reservation->table, function ($mail) {
                return $mail->line("Table: {$this->reservation->table->name}");
            })
            ->line("Confirmation Code: **{$this->reservation->confirmation_code}**")
            ->when($this->reservation->special_requests, function ($mail) {
                return $mail->line("Special Requests: {$this->reservation->special_requests}");
            })
            ->line('Please arrive on time. If you need to cancel or modify your reservation, please contact us as soon as possible.')
            ->line('We look forward to seeing you!')
            ->salutation("Best regards,\n{$restaurantName}");
    }
}
