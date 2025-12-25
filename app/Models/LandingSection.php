<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'landing_page_id',
        'type',
        'title',
        'subtitle',
        'content',
        'order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'json',
    ];

    public function page()
    {
        return $this->belongsTo(LandingPage::class, 'landing_page_id');
    }

    public function items()
    {
        return $this->hasMany(LandingItem::class)->orderBy('order');
    }
}
