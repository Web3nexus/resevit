<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'investor_id',
        'opportunity_id',
        'amount',
        'current_value',
        'status',
        'contract_path',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(InvestmentOpportunity::class, 'opportunity_id');
    }

    public function payouts()
    {
        return $this->hasMany(InvestorPayout::class);
    }
}
