<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Chat extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'uuid',
        'source',
        'external_chat_id',
        'customer_name',
        'customer_handle',
        'status',
        'last_message_at',
        'unread_count',
    ];

    protected $appends = ['is_ai_active'];

    protected $casts = [
        'last_message_at' => 'datetime',
        'unread_count' => 'integer',
        'is_ai_active' => 'boolean',
    ];

    public function getIsAiActiveAttribute(): bool
    {
        return $this->automationLogs()
            ->where('status', 'in_progress')
            ->whereHas('flow', fn($q) => $q->where('trigger_type', 'ai_assistant'))
            ->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function automationLogs(): HasMany
    {
        return $this->hasMany(AutomationLog::class);
    }
}
