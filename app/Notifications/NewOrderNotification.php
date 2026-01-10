<?php

namespace App\Notifications;

class NewOrderNotification extends BaseNotification
{
    protected string $templateKey = 'business_new_order';

    public function __construct(
        protected array $orderData
    ) {
        $this->data = $orderData;
    }
}
