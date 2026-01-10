<?php

namespace App\Notifications;

class StaffPromotionNotification extends BaseNotification
{
    protected string $templateKey = 'staff_promotion';

    public function __construct(
        protected array $promotionData
    ) {
        $this->data = $promotionData;
    }
}
