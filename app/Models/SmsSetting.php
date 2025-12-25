<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'use_default',
        'provider',
        'api_key',
        'api_secret',
        'from_number',
        'api_region',
        'is_active',
    ];

    protected $casts = [
        'use_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'api_secret' => 'encrypted',
        ];
    }
}
