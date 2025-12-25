<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $connection = 'tenant';
    

    protected $fillable = [
        'chat_id',
        'direction',
        'content',
        'external_message_id',
        'metadata',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
