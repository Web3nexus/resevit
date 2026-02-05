<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPayout;
use App\Models\StaffWorkLog;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Get overall payroll stats and list of staff with their accruals.
     */
    public function index()
    {
        $staff = Staff::with('user')->get();

        $data = $staff->map(function (\App\Models\Staff $member) {
            return [
                'staff_id' => $member->id,
                'name' => $member->user->name ?? 'Unknown',
                'hourly_rate' => $member->hourly_rate,
                'total_hours' => $member->workLogs()->sum('hours_worked'),
                'total_earned' => $member->total_paid, // Uses model attribute
                'pending_payout' => $member->pending_payout, // Uses model attribute
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'stats' => [
                'total_payroll_processed' => StaffPayout::where('status', 'paid')->sum('amount'),
                'upcoming_payouts' => StaffPayout::where('status', 'pending')->sum('amount'),
                'active_staff' => Staff::where('status', 'active')->count(),
            ]
        ]);
    }

    /**
     * Get payout history for a specific staff member.
     */
    public function staffHistory(Staff $staff)
    {
        return response()->json([
            'success' => true,
            'data' => $staff->payouts()->latest()->get(),
            'work_logs' => $staff->workLogs()->latest()->limit(20)->get(),
        ]);
    }

    /**
     * Process a payout for a staff member.
     */
    public function processPayout(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $payout = $staff->payouts()->create([
            'amount' => $validated['amount'],
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $validated['method'] ?? 'bank_transfer',
            'note' => $validated['note'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout processed successfully',
            'data' => $payout
        ]);
    }
}
