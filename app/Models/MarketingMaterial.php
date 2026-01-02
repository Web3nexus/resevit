<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingMaterial extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'title',
        'description',
        'type', // image, link, text
        'file_path',
        'url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
