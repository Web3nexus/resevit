<?php

namespace App\Notifications;

class BaseNotificationImplementation extends BaseNotification
{
    public function __construct(string $templateKey, array $data = [])
    {
        $this->templateKey = $templateKey;
        $this->data = $data;
    }
}
