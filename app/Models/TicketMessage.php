<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketMessage extends Model
{
    use HasFactory;

    protected $connection = 'landlord';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'user_type',
        'message',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function sender(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
