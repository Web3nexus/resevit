<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UptimePulse extends Model
{
    protected $connection = 'landlord';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'cpu_usage',
        'memory_usage',
        'disk_usage',
        'payload',
        'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];
}
