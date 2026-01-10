<?php

namespace App\Notifications;

class SuperAdminNewCustomerNotification extends BaseNotification
{
    protected string $templateKey = 'super_admin_new_customer';

    public function __construct(
        protected array $customerData
    ) {
        $this->data = $customerData;
    }
}
