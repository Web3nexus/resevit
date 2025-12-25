<?php

namespace App\Services\Finance;

use App\Models\Investment;
use App\Models\InvestmentOpportunity;
use App\Models\Investor;
use App\Models\InvestorPayout;
use Illuminate\Support\Facades\DB;

class InvestmentService
{
    /**
     * Process a new investment.
     */
    public function invest(Investor $investor, InvestmentOpportunity $opportunity, float $amount): Investment
    {
        return DB::connection('landlord')->transaction(function () use ($investor, $opportunity, $amount) {
            // 1. Validation
            if ($investor->wallet_balance < $amount) {
                throw new \Exception("Insufficient wallet balance.");
            }

            if ($opportunity->status !== 'active') {
                throw new \Exception("Investment opportunity is not active.");
            }

            if ($amount < $opportunity->min_investment) {
                throw new \Exception("Minimum investment amount is " . $opportunity->min_investment);
            }

            // 2. Deduct from wallet
            $investor->decrement('wallet_balance', $amount);

            // 3. Create Investment
            $investment = Investment::create([
                'investor_id' => $investor->id,
                'opportunity_id' => $opportunity->id,
                'amount' => $amount,
                'current_value' => $amount,
                'status' => 'completed',
            ]);

            // 4. Update Opportunity
            $opportunity->increment('raised_amount', $amount);

            // 5. Check if fully funded
            if ($opportunity->raised_amount >= $opportunity->target_amount) {
                $opportunity->update(['status' => 'funded']);
            }

            return $investment;
        });
    }

    /**
     * Process a payout to an investor.
     */
    public function payout(Investment $investment, float $amount, string $type = 'ROI'): InvestorPayout
    {
        return DB::connection('landlord')->transaction(function () use ($investment, $amount, $type) {
            // 1. Create Payout record
            $payout = InvestorPayout::create([
                'investor_id' => $investment->investor_id,
                'investment_id' => $investment->id,
                'amount' => $amount,
                'status' => 'paid',
                'payout_date' => now(),
            ]);

            // 2. Update Investor Wallet
            $investment->investor->increment('wallet_balance', $amount);

            return $payout;
        });
    }
}
