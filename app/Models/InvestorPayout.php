<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorPayout extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'investor_id',
        'investment_id',
        'amount',
        'status',
        'payout_date',
        'reference_id',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }
}
