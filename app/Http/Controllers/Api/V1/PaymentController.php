<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * List all payments and transactions.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::latest()->get();
        $payments = Payment::latest()->limit(50)->get();

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'payments' => $payments,
            'stats' => [
                'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
                'pending_payouts' => Transaction::where('status', 'pending')->sum('amount'),
                'last_payout' => Transaction::where('status', 'completed')->latest()->first()?->amount ?? 0,
            ]
        ]);
    }

    /**
     * Get details for a specific transaction.
     */
    public function showTransaction(Transaction $transaction)
    {
        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }
}
