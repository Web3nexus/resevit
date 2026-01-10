<?php

namespace App\Notifications;

class StaffCredentialsNotification extends BaseNotification
{
    protected string $templateKey = 'staff_credentials';

    public function __construct(
        protected array $credentialsData
    ) {
        $this->data = $credentialsData;
    }
}
