<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'use_default',
        'provider',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'api_key',
        'api_region',
        'from_email',
        'from_name',
        'is_active',
    ];

    protected $casts = [
        'use_default' => 'boolean',
        'is_active' => 'boolean',
        'smtp_port' => 'integer',
    ];

    protected $hidden = [
        'smtp_password',
        'api_key',
    ];

    protected function casts(): array
    {
        return [
            'smtp_password' => 'encrypted',
            'api_key' => 'encrypted',
        ];
    }
}
