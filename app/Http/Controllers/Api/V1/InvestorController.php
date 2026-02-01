<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\InvestmentOpportunity;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestorController extends Controller
{
    /**
     * List active investment opportunities.
     */
    public function opportunities(Request $request)
    {
        $query = InvestmentOpportunity::query()
            ->with(['media']) // Assuming media library usage for cover images
            ->where('status', 'active')
            ->where('expires_at', '>', now());

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json([
            'data' => $query->latest()->paginate(20)
        ]);
    }

    /**
     * Show a specific opportunity.
     */
    public function showOpportunity(InvestmentOpportunity $opportunity)
    {
        return response()->json([
            'data' => $opportunity->load(['media', 'tenant'])
        ]);
    }

    /**
     * Process an investment.
     */
    public function invest(Request $request)
    {
        $validated = $request->validate([
            'opportunity_id' => 'required|exists:landlord.investment_opportunities,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = (float) $validated['amount'];
        $user = $request->user(); // Auth::user()

        // 1. Check Opportunity Validity
        $opportunity = InvestmentOpportunity::findOrFail($validated['opportunity_id']);

        if ($opportunity->status !== 'active' || $opportunity->expires_at < now()) {
            return response()->json(['message' => 'Opportunity is not active.'], 400);
        }

        if ($amount < $opportunity->min_investment) {
            return response()->json(['message' => "Minimum investment is {$opportunity->min_investment}"], 400);
        }

        $remaining = $opportunity->target_amount - $opportunity->raised_amount;
        if ($amount > $remaining) {
            return response()->json(['message' => "Only {$remaining} available for investment"], 400);
        }

        // 2. Check User Wallet Balance
        if ($user->wallet_balance < $amount) {
            return response()->json(['message' => 'Insufficient wallet balance.'], 402);
        }

        try {
            DB::connection('landlord')->beginTransaction();

            // Deduct balance
            $user->wallet_balance -= $amount;
            $user->save();

            // Record Transaction
            $user->transactions()->create([
                'amount' => -$amount,
                'type' => 'investment',
                'status' => 'completed',
                'description' => "Investment in {$opportunity->title}",
                'transactionable_type' => InvestmentOpportunity::class,
                'transactionable_id' => $opportunity->id,
            ]);

            // Create Investment
            $investment = Investment::create([
                'investor_id' => $user->id, // Assuming user is the investor or linked to one
                'opportunity_id' => $opportunity->id,
                'amount' => $amount,
                'current_value' => $amount, // Initially same as invested
                'status' => 'active',
            ]);

            // Update Opportunity Raised Amount
            $opportunity->increment('raised_amount', $amount);

            DB::connection('landlord')->commit();

            return response()->json([
                'message' => 'Investment successful',
                'data' => $investment
            ], 201);

        } catch (\Exception $e) {
            DB::connection('landlord')->rollBack();
            return response()->json(['message' => 'Investment failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get user's portfolio.
     */
    public function portfolio(Request $request)
    {
        $investments = Investment::where('investor_id', $request->user()->id)
            ->with(['opportunity'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $investments
        ]);
    }

    /**
     * Get user's wallet balance and history.
     */
    public function wallet(Request $request)
    {
        $user = $request->user();

        $history = $user->transactions()
            ->latest()
            ->paginate(20);

        return response()->json([
            'balance' => $user->wallet_balance,
            'history' => $history
        ]);
    }
}
