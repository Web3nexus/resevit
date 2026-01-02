<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // validate request
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_id' => 'nullable|exists:variants,id',
            'items.*.addons' => 'nullable|array',
            'items.*.addons.*' => 'exists:addons,id',
            'customer_name' => 'required|string',
            'customer_phone' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'items_total' => 'required|numeric',
            'tax_total' => 'required|numeric',
            'delivery_fee' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'table_id' => 'nullable|exists:tables,id',
            'address' => 'nullable|string|required_if:order_type,delivery',
        ]);

        try {
            DB::beginTransaction();

            // Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'items_total' => $validated['items_total'],
                'tax_total' => $validated['tax_total'],
                'delivery_fee' => $validated['delivery_fee'],
                'total_amount' => $validated['total_amount'],
                'order_type' => $validated['order_type'],
                'table_id' => $validated['table_id'] ?? null,
                'delivery_address' => $validated['address'] ?? null,
            ]);

            // Create Items
            foreach ($validated['items'] as $itemData) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $itemData['menu_item_id'],
                    'quantity' => $itemData['quantity'],
                    'variant_id' => $itemData['variant_id'] ?? null,
                    'price' => 0, // Should be calculated/verified server side in production
                    'total_price' => 0, // Placeholder
                ]);

                if (!empty($itemData['addons'])) {
                    foreach ($itemData['addons'] as $addonId) {
                        OrderItemAddon::create([
                            'order_item_id' => $orderItem->id,
                            'addon_id' => $addonId,
                            'price' => 0, // Placeholder
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'data' => $order->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        return response()->json([
            'data' => $order->load(['items.menuItem', 'items.variant', 'items.addons'])
        ]);
    }
}
