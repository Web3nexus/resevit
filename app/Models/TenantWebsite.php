<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class TenantWebsite extends Model
{
    use CentralConnection, HasFactory;

    protected $table = 'tenant_websites';

    protected $fillable = [
        'tenant_id',
        'website_template_id',
        'content',
        'settings',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function template()
    {
        return $this->belongsTo(WebsiteTemplate::class, 'website_template_id');
    }
}
