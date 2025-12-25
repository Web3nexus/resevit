<?php

namespace App\Models;

class TenantUser extends User
{
    protected $connection = 'tenant';


    protected $table = 'users';

    public $guard_name = 'web';
}
