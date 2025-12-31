<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $connection = 'tenant';

    protected $fillable = [
        'branch_id',
        'name',
        'type',
        'value',
        'code',
        'start_date',
        'end_date',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function isExpired(): bool
    {
        if ($this->end_date && $this->end_date->isPast()) {
            return true;
        }

        return false;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }
}
