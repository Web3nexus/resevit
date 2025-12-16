<?php

namespace App\Models;

class LandlordUser extends User
{
    protected $connection = 'landlord';
    protected $table = 'users'; // Explicitly set table just in case
}
