<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            // Initial totals calculation
            $calculatedItemsTotal = 0;

            // Prepare items and calculate totals first to ensure final order amount is correct
            $itemsToCreate = [];
            foreach ($validated['items'] as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);
                $itemPrice = (float) $menuItem->base_price;

                $variantPriceAdjustment = 0;
                if (!empty($itemData['variant_id'])) {
                    $variant = Variant::where('menu_item_id', $menuItem->id)->findOrFail($itemData['variant_id']);
                    $variantPriceAdjustment = (float) $variant->price_adjustment;
                }

                $addonTotal = 0;
                $addons = [];
                if (!empty($itemData['addons'])) {
                    foreach ($itemData['addons'] as $addonId) {
                        $addon = Addon::findOrFail($addonId);
                        $addonTotal += (float) $addon->price;
                        $addons[] = [
                            'id' => $addon->id,
                            'price' => (float) $addon->price,
                        ];
                    }
                }

                $unitPrice = $itemPrice + $variantPriceAdjustment + $addonTotal;
                $totalItemPrice = $unitPrice * $itemData['quantity'];
                $calculatedItemsTotal += $totalItemPrice;

                $itemsToCreate[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $itemData['quantity'],
                    'variant_id' => $itemData['variant_id'] ?? null,
                    'price' => $unitPrice,
                    'total_price' => $totalItemPrice,
                    'addons' => $addons,
                ];
            }

            // Realistically tax and delivery should be calculated here too
            // For now, we'll trust validated values but we could override them if needed
            $taxTotal = $validated['tax_total'];
            $deliveryFee = ($validated['order_type'] === 'delivery') ? $validated['delivery_fee'] : 0;
            $totalAmount = $calculatedItemsTotal + $taxTotal + $deliveryFee;

            // Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'items_total' => $calculatedItemsTotal,
                'tax_total' => $taxTotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'order_type' => $validated['order_type'],
                'table_id' => $validated['table_id'] ?? null,
                'delivery_address' => $validated['address'] ?? null,
            ]);

            // Create Items
            foreach ($itemsToCreate as $itemData) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $itemData['menu_item_id'],
                    'quantity' => $itemData['quantity'],
                    'variant_id' => $itemData['variant_id'],
                    'price' => $itemData['price'],
                    'total_price' => $itemData['total_price'],
                ]);

                foreach ($itemData['addons'] as $addonData) {
                    OrderItemAddon::create([
                        'order_item_id' => $orderItem->id,
                        'addon_id' => $addonData['id'],
                        'price' => $addonData['price'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'data' => $order->load('items'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to place order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $query = Order::query()->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Optional: Filter by today/active only
        if ($request->boolean('active_only')) {
            $query->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
        }

        return response()->json([
            'data' => $query->paginate(20)
        ]);
    }

    public function show(Order $order)
    {
        return response()->json([
            'data' => $order->load(['items.menuItem', 'items.variant', 'items.addons']),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        // Optional: Send notification to customer here

        return response()->json([
            'message' => 'Order status updated',
            'data' => $order
        ]);
    }
}
