<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationTrigger extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'automation_flow_id',
        'trigger_key',
        'trigger_value',
    ];

    public function flow(): BelongsTo
    {
        return $this->belongsTo(AutomationFlow::class, 'automation_flow_id');
    }
}
