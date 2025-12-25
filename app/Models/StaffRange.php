<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffRange extends Model
{
    protected $connection = 'landlord';
    protected $fillable = ['range', 'label'];
}
