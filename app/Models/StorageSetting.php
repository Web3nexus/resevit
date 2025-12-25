<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageSetting extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'active_disk',
        'cdn_url',
        's3_key',
        's3_secret',
        's3_region',
        's3_bucket',
        's3_endpoint',
        'cloudflare_api_token',
        'cloudflare_zone_id',
        'cloudflare_account_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        's3_secret',
        'cloudflare_api_token',
    ];
}
