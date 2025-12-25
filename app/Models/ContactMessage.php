<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'is_read',
    ];
}
