<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomationFlow extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'steps',
        'is_active',
    ];

    protected $casts = [
        'steps' => 'array',
        'is_active' => 'boolean',
    ];

    public function triggers(): HasMany
    {
        return $this->hasMany(AutomationTrigger::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AutomationLog::class);
    }
}
