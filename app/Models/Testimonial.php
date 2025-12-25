<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    protected $connection = 'landlord';
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'role',
        'company',
        'content',
        'rating',
        'order',
        'is_active',
    ];
}
