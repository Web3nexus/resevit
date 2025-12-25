<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'name',
        'subject',
        'content',
        'recipient_type',
        'status',
        'scheduled_at',
        'sent_at',
        'recipients_count',
        'sent_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'recipients_count' => 'integer',
        'sent_count' => 'integer',
    ];
}
