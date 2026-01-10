<?php

namespace App\Notifications;

class StaffTerminationNotification extends BaseNotification
{
    protected string $templateKey = 'staff_termination';

    public function __construct(
        protected array $terminationData
    ) {
        $this->data = $terminationData;
    }
}
