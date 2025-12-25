<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    public function sections()
    {
        return $this->hasMany(LandingSection::class)->orderBy('order');
    }
}
