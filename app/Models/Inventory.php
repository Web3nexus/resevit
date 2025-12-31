<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes, BelongsToBranch;

    protected $connection = 'tenant';
    protected $table = 'inventory';

    protected $fillable = [
        'branch_id',
        'name',
        'sku',
        'current_stock',
        'unit',
        'low_stock_threshold',
        'last_restocked_at',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'low_stock_threshold' => 'decimal:2',
        'last_restocked_at' => 'datetime',
    ];
}
