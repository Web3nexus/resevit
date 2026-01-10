<?php

namespace App\Notifications;

class StaffWelcomeNotification extends BaseNotification
{
    protected string $templateKey = 'staff_welcome';

    public function __construct(
        protected array $staffData
    ) {
        $this->data = $staffData;
    }
}
