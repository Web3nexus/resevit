<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSmsSetting extends Model
{
    protected $connection = 'landlord';
    
    protected $fillable = [
        'provider',
        'api_key',
        'api_secret',
        'from_number',
        'api_region',
        'is_active',
    ];

    protected $casts = [
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
