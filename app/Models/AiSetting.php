<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\Input;

class AiSetting extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'provider',
        'api_key',
        'chat_model',
        'premium_model',
        'image_model',
        'embedding_model',
        'code_model',
        'is_active',
        'rate_limits',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rate_limits' => 'array',
    ];

    protected $hidden = [
        'api_key', // Never expose in JSON
    ];
}
