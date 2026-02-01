<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    // Tenant connection is default for models not specifying connection
    // protected $connection = 'tenant';

    protected $fillable = [
        'type', // email or sms
        'key',
        'name',
        'subject', // email only
        'content', // html for email, text for sms
        'plain_content', // text version for email
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
}
