<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $templateKey;
    protected array $data = [];

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        // Configure mail dynamically from database settings
        MailConfigService::configureMail();

        $template = EmailTemplate::where('key', $this->templateKey)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            Log::error("Email template not found: {$this->templateKey}");

            // Fallback if template is missing but we must send something
            return (new MailMessage)
                ->error()
                ->subject('Critical: Missing Email Template')
                ->line("The system tried to send an email with key '{$this->templateKey}', but the template was not found.");
        }


        $rendered = $template->render($this->getMergeData($notifiable));

        $mail = (new MailMessage)
            ->subject($rendered['subject'])
            ->view('emails.rendered', ['content' => $rendered['body_html']]);

        if (!empty($rendered['body_text'])) {
            $mail->text('emails.rendered-text', ['content' => $rendered['body_text']]);
        }

        return $mail;
    }


    /**
     * Prepare data for template replacement
     */
    protected function getMergeData($notifiable): array
    {
        return array_merge([
            'user_name' => $notifiable->name ?? 'User',
            'user_email' => $notifiable->email ?? '',
            'app_url' => config('app.url'),
            'app_name' => config('app.name'),
        ], $this->data);
    }
}
