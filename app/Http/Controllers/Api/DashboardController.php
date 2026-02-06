<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Table;
use App\Models\Message;
use App\Models\Staff;
use App\Models\Task;
use App\Models\Branch;
use App\Models\StaffPayout;
use App\Models\OrderItem;
use App\Models\PlatformMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Starting');
        $user = $request->user();
        $tenant = tenant();
        $branchId = $user->currentBranchId ?? null;

        try {
            // Base queries
            $reservationsQuery = Reservation::query();
            $ordersQuery = Order::query();
            $staffQuery = Staff::query();
            $tasksQuery = Task::query();
            $branchesQuery = Branch::query();

            if ($branchId) {
                $reservationsQuery->where('branch_id', $branchId);
                $ordersQuery->where('branch_id', $branchId);
                $tasksQuery->where('branch_id', $branchId);
            }

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 1');
            $walletBalance = (float) ($user->wallet_balance ?? 0);

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - StaffPayout query');
            $totalPayout = (float) StaffPayout::paid()->sum('amount');

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - AI Credits');
            $aiCredits = (float) ($tenant->ai_credits ?? 0);

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 2 Earnings');
            $grossEarnings = (float) $ordersQuery->clone()->completed()->sum('total_amount');
            $platformFeeRate = 0.05;
            $platformFees = $grossEarnings * $platformFeeRate;
            $netEarnings = $grossEarnings - $platformFees;

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 3 Counts');
            $totalStaff = $staffQuery->count();
            $totalTasks = $tasksQuery->count();
            $branchesCount = $branchesQuery->count();

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 4 Sales');
            $totalSalesCount = $ordersQuery->clone()->completed()->count();
            $avgOrderValue = $totalSalesCount > 0 ? $grossEarnings / $totalSalesCount : 0;
            $onlineOrdersCount = $ordersQuery->clone()->online()->count();

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 5 Most Selling');
            $mostSellingProducts = OrderItem::select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
                ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                ->groupBy('order_items.menu_item_id', 'menu_items.name')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->get();

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 5 Recent Orders');
            $recentOrders = $ordersQuery->clone()->latest()->limit(5)->get()->map(fn($o) => [
                'id' => $o->id,
                'number' => $o->order_number,
                'total' => (float) $o->total_amount,
                'status' => $o->status,
                'created_at' => $o->created_at->toIso8601String(),
            ]);

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 6 Recent Messages');
            $recentMessages = PlatformMessage::where('sender_id', '!=', $user->id)
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($m) => [
                    'id' => $m->id,
                    'sender' => $m->sender_name,
                    'body' => $m->body,
                    'created_at' => $m->created_at->toIso8601String(),
                ]);

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 6 Recent Reservations');
            $recentReservations = $reservationsQuery->clone()->latest()->limit(5)->get()->map(fn($r) => [
                'id' => $r->id,
                'guest' => $r->customer_name ?? 'Guest',
                'time' => $r->reservation_time,
                'guests' => $r->number_of_guests,
                'status' => $r->status,
            ]);

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Row 7 Reservation Sources');
            $reservationSources = Reservation::select('source', DB::raw('count(*) as total'))
                ->whereNotNull('source')
                ->groupBy('source')
                ->get();

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Final Trends');
            $totalRevenue = $grossEarnings;
            $activeOrders = $ordersQuery->clone()->where('status', 'preparing')->count();
            $totalReservationsCount = $reservationsQuery->clone()->count();
            $todayReservationsCount = $reservationsQuery->clone()->whereDate('reservation_time', now())->count();
            $activeStaffCount = Staff::where('status', 'active')->count();

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Low Stock Items');
            $lowStockItemsCount = \App\Models\MenuItem::where('is_available', true)
                ->where('description', 'like', '%low stock%')
                ->count();

            \Illuminate\Support\Facades\Log::info('DEBUG: getStats - Returning Response');
            return response()->json([
                'success' => true,
                'total_revenue' => $totalRevenue,
                'revenue_trend' => 8.5,
                'active_orders' => $activeOrders,
                'orders_trend' => 12,
                'total_reservations' => $totalReservationsCount,
                'reservations_trend' => -2.4,
                'today_reservations' => $todayReservationsCount,
                'active_staff_count' => $activeStaffCount,
                'low_stock_items' => $lowStockItemsCount,
                'stats' => [
                    'wallet_balance' => $walletBalance,
                    'total_payout' => $totalPayout,
                    'ai_credits' => $aiCredits,
                    'gross_earnings' => $grossEarnings,
                    'platform_fees' => $platformFees,
                    'net_earnings' => $netEarnings,
                    'total_staff' => $totalStaff,
                    'total_tasks' => $totalTasks,
                    'branches_count' => $branchesCount,
                    'total_sales' => $totalSalesCount,
                    'avg_order_value' => $avgOrderValue,
                    'online_orders' => $onlineOrdersCount,
                ],
                'most_selling_products' => $mostSellingProducts,
                'recent_orders' => $recentOrders,
                'recent_messages' => $recentMessages,
                'recent_reservations' => $recentReservations,
                'reservation_sources' => $reservationSources,
                'ai_insights' => [
                    'Your revenue is up 12% compared to last week.',
                    'Saturday 7 PM is your busiest time. Ensure more staff are on duty.',
                    'Popular product "Burger Extra" is low on stock.',
                ],
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('DEBUG: getStats CRASHED: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
                'reservation_date' => $reservation->reservation_time ? $reservation->reservation_time->format('Y-m-d') : null,
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
     * TODO: Implement when Message model is created
     */
    public function getMessages(Request $request)
    {
        // $userId = $request->user()->id;

        // // Get unread messages count
        // $unreadCount = Message::where('recipient_id', $userId)
        //     ->where('is_read', false)
        //     ->count();

        // // Get recent messages
        // $messages = Message::where('recipient_id', $userId)
        //     ->with('sender')
        //     ->orderBy('created_at', 'desc')
        //     ->limit(10)
        //     ->get()
        //     ->map(function ($message) {
        //         return [
        //             'id' => $message->id,
        //             'sender_name' => $message->sender->name ?? 'System',
        //             'subject' => $message->subject,
        //             'body' => $message->body,
        //             'is_read' => $message->is_read,
        //             'created_at' => $message->created_at->toIso8601String(),
        //         ];
        //     });

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => 0,
                'messages' => [],
            ],
        ]);
    }
}
