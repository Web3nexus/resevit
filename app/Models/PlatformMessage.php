<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformMessage extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'platform_conversation_id',
        'sender_type',
        'sender_id',
        'body',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(PlatformConversation::class, 'platform_conversation_id');
    }

    public function sender(): BelongsTo
    {
        if ($this->sender_type === 'admin') {
            return $this->belongsTo(Admin::class, 'sender_id');
        }
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getSenderNameAttribute(): string
    {
        if ($this->sender_type === 'guest') {
            return $this->conversation->name ?? 'Guest';
        }

        return $this->sender?->name ?? 'Unknown';
    }
}
