<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfLastMonth();

        // 1. Revenue Stats
        $currentRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');

        $revenueTrend = $lastMonthRevenue > 0
            ? round((($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
            : 0;

        // 2. Active Orders
        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])->count();

        $lastMonthOrders = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $currentMonthOrders = Order::where('created_at', '>=', $startOfMonth)->count();
        $ordersTrend = $lastMonthOrders > 0
            ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 2)
            : 0;

        // 3. Monthly Reservations
        $totalReservations = Reservation::where('reservation_time', '>=', $startOfMonth)
            ->where('status', '!=', 'cancelled')
            ->count();

        $lastMonthReservations = Reservation::whereBetween('reservation_time', [$startOfLastMonth, $endOfLastMonth])
            ->where('status', '!=', 'cancelled')
            ->count();

        $reservationsTrend = $lastMonthReservations > 0
            ? round((($totalReservations - $lastMonthReservations) / $lastMonthReservations) * 100, 2)
            : 0;

        // 4. Today's Reservations
        $todayReservations = Reservation::whereDate('reservation_time', $now->toDateString())
            ->where('status', '!=', 'cancelled')
            ->count();

        // 5. Active Staff Count (Checking checked_in staff)
        $activeStaffCount = \App\Models\Staff::whereHas('workLogs', function ($query) {
            $query->whereNull('check_out');
        })->count();

        // 6. Low Stock (Placeholder logic)
        $lowStockItems = MenuItem::where('is_available', false)->count();

        return response()->json([
            'total_revenue' => (float) $currentRevenue,
            'revenue_trend' => (float) $revenueTrend,
            'active_orders' => $activeOrders,
            'orders_trend' => (float) $ordersTrend,
            'total_reservations' => $totalReservations, // Monthly
            'reservations_trend' => (float) $reservationsTrend,
            'today_reservations' => $todayReservations,
            'active_staff_count' => $activeStaffCount,
            'low_stock_items' => $lowStockItems,
        ]);
    }
}
