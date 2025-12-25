<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingTemplate extends Model
{
    protected $connection = 'tenant';
    

    protected $fillable = [
        'name',
        'type',
        'subject',
        'content',
    ];
}
