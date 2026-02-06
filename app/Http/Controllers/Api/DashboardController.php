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
        \Illuminate\Support\Facades\Log::info('DEBUG: DashboardController@getStats hit', ['user_id' => $request->user()->id]);
        $user = $request->user();
        $tenant = tenant();
        $branchId = $user->currentBranchId ?? null;

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

        // --- Row 1 Stats ---
        $walletBalance = (float) ($user->wallet_balance ?? 0);
        $totalPayout = (float) StaffPayout::paid()->sum('amount');
        $aiCredits = (float) ($tenant->ai_credits ?? 0);

        // --- Row 2 Stats ---
        $grossEarnings = (float) $ordersQuery->clone()->completed()->sum('total_amount');
        $platformFeeRate = 0.05; // Mock: 5% platform fee
        $platformFees = $grossEarnings * $platformFeeRate;
        $netEarnings = $grossEarnings - $platformFees;

        // --- Row 3 Stats ---
        $totalStaff = $staffQuery->count();
        $totalTasks = $tasksQuery->count();
        $branchesCount = $branchesQuery->count();

        // --- Row 4 Stats ---
        $totalSalesCount = $ordersQuery->clone()->completed()->count();
        $avgOrderValue = $totalSalesCount > 0 ? $grossEarnings / $totalSalesCount : 0;
        $onlineOrdersCount = $ordersQuery->clone()->online()->count();

        // --- Row 5 Data (Products & Orders) ---
        $mostSellingProducts = OrderItem::select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->groupBy('order_items.menu_item_id', 'menu_items.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $recentOrders = $ordersQuery->clone()->latest()->limit(5)->get()->map(fn($o) => [
            'id' => $o->id,
            'number' => $o->order_number,
            'total' => (float) $o->total_amount,
            'status' => $o->status,
            'created_at' => $o->created_at->toIso8601String(),
        ]);

        // --- Row 6 Data (Messages & Reservations) ---
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

        $recentReservations = $reservationsQuery->clone()->latest()->limit(5)->get()->map(fn($r) => [
            'id' => $r->id,
            'guest' => $r->customer_name ?? 'Guest',
            'time' => $r->reservation_time,
            'guests' => $r->number_of_guests,
            'status' => $r->status,
        ]);

        // --- Row 7 Data (Source & AI) ---
        $reservationSources = Reservation::select('source', DB::raw('count(*) as total'))
            ->whereNotNull('source')
            ->groupBy('source')
            ->get();

        // --- Calculations for Flutter DashboardStats Model ---
        $totalRevenue = $grossEarnings;
        $revenueTrend = 8.5; // Mock trend
        $activeOrders = $ordersQuery->clone()->where('status', 'preparing')->count();
        $ordersTrend = 12; // Mock trend
        $totalReservationsCount = $reservationsQuery->clone()->count();
        $reservationsTrend = -2.4; // Mock trend
        $todayReservationsCount = $reservationsQuery->clone()->whereDate('reservation_time', now())->count();
        $activeStaffCount = Staff::where('status', 'active')->count();
        $lowStockItemsCount = \App\Models\MenuItem::where('is_available', true)
            ->where('description', 'like', '%low stock%')
            ->count();

        return response()->json([
            'success' => true,
            'total_revenue' => $totalRevenue,
            'revenue_trend' => $revenueTrend,
            'active_orders' => $activeOrders,
            'orders_trend' => $ordersTrend,
            'total_reservations' => $totalReservationsCount,
            'reservations_trend' => $reservationsTrend,
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
