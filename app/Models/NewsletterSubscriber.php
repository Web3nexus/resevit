<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'email',
        'is_active',
    ];
}
