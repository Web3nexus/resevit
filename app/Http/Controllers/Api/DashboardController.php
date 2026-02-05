<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Table;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        $branchId = $request->user()->currentBranchId ?? null;

        // Build queries with optional branch filtering
        $reservationsQuery = Reservation::query();
        $ordersQuery = Order::query();
        $customersQuery = Customer::query();
        $tablesQuery = Table::query();

        if ($branchId) {
            $reservationsQuery->where('branch_id', $branchId);
            $ordersQuery->where('branch_id', $branchId);
            $tablesQuery->where('branch_id', $branchId);
        }

        // Calculate stats
        $totalReservations = $reservationsQuery->count();
        $todayReservations = $reservationsQuery->whereDate('reservation_date', today())->count();

        $totalRevenue = $ordersQuery->where('status', 'completed')->sum('total_amount') ?? 0;
        $todayRevenue = $ordersQuery->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount') ?? 0;

        $totalCustomers = $customersQuery->count();
        $totalTables = $tablesQuery->count();
        $availableTables = $tablesQuery->where('status', 'available')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'reservations' => [
                    'total' => $totalReservations,
                    'today' => $todayReservations,
                ],
                'revenue' => [
                    'total' => number_format($totalRevenue, 2),
                    'today' => number_format($todayRevenue, 2),
                    'currency' => 'USD',
                ],
                'customers' => [
                    'total' => $totalCustomers,
                ],
                'tables' => [
                    'total' => $totalTables,
                    'available' => $availableTables,
                    'occupied' => $totalTables - $availableTables,
                ],
            ],
        ]);
    }

    /**
     * Get recent reservations
     */
    public function getRecentReservations(Request $request)
    {
        $branchId = $request->user()->currentBranchId ?? null;

        $query = Reservation::with(['customer', 'table'])
            ->orderBy('created_at', 'desc')
            ->limit(10);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $reservations = $query->get()->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'customer_name' => $reservation->customer->name ?? 'Guest',
                'customer_phone' => $reservation->customer->phone ?? null,
                'table_name' => $reservation->table->name ?? 'N/A',
                'reservation_date' => $reservation->reservation_date,
                'reservation_time' => $reservation->reservation_time,
                'guests' => $reservation->number_of_guests,
                'status' => $reservation->status,
                'created_at' => $reservation->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }

    /**
     * Get messages/notifications
     */
    public function getMessages(Request $request)
    {
        $userId = $request->user()->id;

        // Get unread messages count
        $unreadCount = Message::where('recipient_id', $userId)
            ->where('is_read', false)
            ->count();

        // Get recent messages
        $messages = Message::where('recipient_id', $userId)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_name' => $message->sender->name ?? 'System',
                    'subject' => $message->subject,
                    'body' => $message->body,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $unreadCount,
                'messages' => $messages,
            ],
        ]);
    }
}
