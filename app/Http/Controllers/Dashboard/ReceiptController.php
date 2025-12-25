<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function show(Order $order)
    {
        return view('filament.dashboard.pages.receipt', [
            'order' => $order->load(['items.menuItem', 'items.variant', 'staff.user', 'payments', 'table']),
            'tenant' => tenant(),
        ]);
    }
}
