<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SitePage extends Model
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'slug',
        'config',
        'is_published',
    ];

    protected $casts = [
        'config' => 'array',
        'is_published' => 'boolean',
    ];

    /**
     * Scope for the home page.
     */
    public function scopeHome($query)
    {
        return $query->where('slug', 'index');
    }
}
