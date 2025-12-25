<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentationArticle extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function getUrlAttribute()
    {
        return route('docs.show', $this->slug);
    }
}
