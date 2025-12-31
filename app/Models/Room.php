<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToBranch;

class Room extends Model
{
    protected $connection = 'tenant';
    use HasFactory, BelongsToBranch;



    protected $fillable = [
        'name',
        'description',
        'branch_id',
    ];

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }
}
