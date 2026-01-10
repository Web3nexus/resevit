<?php

namespace App\Notifications;

class TwoFactorCodeNotification extends BaseNotification
{
    protected string $templateKey = '2fa_code';

    public function __construct(public string $code)
    {
        $this->data = ['code' => $code];
    }

    protected function getMergeData($notifiable): array
    {
        return array_merge(parent::getMergeData($notifiable), [
            '2fa_code' => $this->code,
        ]);
    }
}
