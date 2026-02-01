<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteTemplate extends Model
{
    use HasFactory;

    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'slug',
        'thumbnail_path',
        'structure_schema',
        'default_content',
        'is_active',
        'is_premium',
    ];

    protected $casts = [
        'structure_schema' => 'array',
        'default_content' => 'array',
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
    ];

    public function websites()
    {
        return $this->hasMany(TenantWebsite::class);
    }
}
