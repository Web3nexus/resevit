<?php

namespace App\Models;

class TenantUser extends User
{
    protected $table = 'users';

    public $guard_name = 'web';
}
