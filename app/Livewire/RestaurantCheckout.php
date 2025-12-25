<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class RestaurantCheckout extends Component
{
    public $cart = [];
    public $customerName;
    public $customerPhone;
    public $tableId;
    public $type = 'dine-in'; // dine-in, pickup
    public $notes;

    protected $rules = [
        'customerName' => 'required|string|min:2',
        'customerPhone' => 'nullable|string',
        'type' => 'required|in:dine-in,pickup',
    ];

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        if (empty($this->cart)) {
            return redirect()->route('tenant.menu');
        }
    }

    public function placeOrder()
    {
        $this->validate();

        if (empty($this->cart)) {
            return;
        }

        DB::transaction(function () {
            // Calculate total
            $total = 0;
            foreach ($this->cart as $item) {
                // Adjust price for variants/addons logic if needed
                $total += $item['price'] * $item['quantity'];
            }

            // Create Order
            $order = Order::create([
                'customer_id' => null, // Guest for now
                'table_id' => $this->tableId,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending', // Cash/Pay at counter default
                'type' => $this->type,
                'notes' => "Name: $this->customerName\nPhone: $this->customerPhone\nNotes: $this->notes",
            ]);

            // Create Items
            foreach ($this->cart as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Addons logic would go here if we stored them in cart struct adequately
            }

            Session::forget('cart');

            return redirect()->route('tenant.order.status', $order);
        });
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $subtotal = array_reduce($this->cart, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

        return view('livewire.restaurant-checkout', [
            'subtotal' => $subtotal,
        ]);
    }
}
