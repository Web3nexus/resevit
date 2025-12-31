<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\BelongsToBranch;

class Table extends Model
{
    protected $connection = 'tenant';
    use HasFactory, SoftDeletes, BelongsToBranch;



    protected $fillable = [
        'name',
        'capacity',
        'status',
        'location',
        'room_id',
        'shape',
        'x',
        'y',
        'width',
        'height',
        'rotation',
        'branch_id',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'x' => 'integer',
        'y' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'rotation' => 'integer',
    ];

    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
