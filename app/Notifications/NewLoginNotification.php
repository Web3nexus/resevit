<?php

namespace App\Notifications;

class NewLoginNotification extends BaseNotification
{
    protected string $templateKey = 'new_login_alert';

    public function __construct(array $loginDetails)
    {
        $this->data = $loginDetails;
    }

    /**
     * Prepare data for template replacement
     */
    protected function getMergeData($notifiable): array
    {
        return array_merge(parent::getMergeData($notifiable), [
            'ip_address' => $this->data['ip'] ?? 'Unknown',
            'user_agent' => $this->data['user_agent'] ?? 'Unknown',
            'login_time' => now()->toDayDateTimeString(),
            'device' => $this->data['device'] ?? 'Unknown',
            'location' => $this->data['location'] ?? 'Unknown',
        ]);
    }
}
