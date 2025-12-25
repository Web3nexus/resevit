<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $connection = 'landlord';

    protected $fillable = [
        'ticketable_id',
        'ticketable_type',
        'tenant_id',
        'subject',
        'status',
        'priority',
        'code',
    ];

    public function ticketable(): MorphTo
    {
        return $this->morphTo();
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->code = 'T-' . strtoupper(uniqid());
        });
    }
}
