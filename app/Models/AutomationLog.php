<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationLog extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'chat_id',
        'automation_flow_id',
        'step_index',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'step_index' => 'integer',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function flow(): BelongsTo
    {
        return $this->belongsTo(AutomationFlow::class, 'automation_flow_id');
    }
}
