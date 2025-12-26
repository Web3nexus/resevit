<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $connection = 'landlord';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'rating',
        'comment',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
