<?php

namespace App\Notifications;

class NewReservationNotification extends BaseNotification
{
    protected string $templateKey = 'business_new_reservation';

    public function __construct(
        protected array $reservationData
    ) {
        $this->data = $reservationData;
    }
}
