<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class InvestmentOpportunity extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $connection = 'landlord';

    protected $fillable = [
        'tenant_id',
        'title',
        'type',
        'description',
        'target_amount',
        'raised_amount',
        'min_investment',
        'roi_percentage',
        'equity_percentage',
        'reward_type',
        'reward_details',
        'investment_round',
        'status',
        'validation_status',
        'expires_at',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class, 'opportunity_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
