<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class LandingItem extends Model implements HasMedia
{
    protected $connection = 'landlord';
    use InteractsWithMedia;

    protected $fillable = [
        'landing_section_id',
        'title',
        'subtitle',
        'description',
        'icon',
        'link_url',
        'link_text',
        'extra',
        'order',
        'is_active',
    ];

    protected $casts = [
        'extra' => 'json',
    ];

    public function section()
    {
        return $this->belongsTo(LandingSection::class, 'landing_section_id');
    }
}
