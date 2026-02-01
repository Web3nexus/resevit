<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

class RestaurantCheckout extends Component
{
    public $cart = [];

    public $customerName;

    public $customerPhone;

    public $tableId;

    public $website;

    public $type = 'dine-in'; // dine-in, pickup, takeout

    public $notes;

    protected $rules = [
        'customerName' => 'required|string|min:2',
        'customerPhone' => 'nullable|string',
        'type' => 'required|in:dine-in,pickup,takeout',
    ];

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        $this->website = \App\Models\TenantWebsite::where('tenant_id', tenant('id'))->first();
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
            // Calculate totals
            $subtotal = 0;
            foreach ($this->cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $tax = $subtotal * 0.08; // 8% tax - should be configurable
            $serviceFee = 0; // Can add service fee logic
            $deliveryFee = $this->type === 'delivery' ? 5.00 : 0;
            $total = $subtotal + $tax + $serviceFee + $deliveryFee;

            // Create Order with e-commerce fields
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'customer_email' => auth()->user()->email ?? null,
                'order_type' => $this->type,
                'items' => $this->cart, // Store cart as JSON for online orders
                'subtotal' => $subtotal,
                'tax' => $tax,
                'service_fee' => $serviceFee,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'order_source' => 'online',
                'notes' => $this->notes,
                'table_id' => $this->tableId,
            ]);

            // Also create OrderItems for reporting (optional, since we have JSON)
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            Session::forget('cart');

            // Check for Stripe Connect Account
            $tenantUser = \App\Models\User::where('tenant_id', tenant('id'))
                ->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['owner', 'admin']);
                })->first();

            if ($tenantUser && $tenantUser->stripe_account_id && $tenantUser->stripe_charges_enabled) {
                // Initialize Stripe Payment Intent
                $stripeService = new \App\Services\StripeService;
                $paymentIntent = $stripeService->createPaymentIntent(
                    amount: $total,
                    currency: 'usd',
                    connectedAccountId: $tenantUser->stripe_account_id,
                    applicationFee: $total * 0.03 // 3% Platform Fee
                );

                if ($paymentIntent) {
                    $order->update(['payment_intent_id' => $paymentIntent->id]);
                    Session::put('pending_order_id', $order->id);

                    return redirect()->route('tenant.payment', $order);
                }
            }

            // Redirect to order confirmation (Cash/Counter)
            session()->flash('order_placed', true);
            session()->flash('order_id', $order->id);
            session()->flash('order_number', $order->order_number);
            session()->flash('order_total', $order->total);

            // Send Notifications
            try {
                $notificationService = new \App\Services\NotificationService;
                $notificationService->sendOrderConfirmation($order);
            } catch (\Exception $e) {
                // Log error but don't stop flow
                \Illuminate\Support\Facades\Log::error('Failed to send order notifications: '.$e->getMessage());
            }

            return redirect()->route('tenant.order.confirmation', $order);
        });
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $subtotal = array_reduce($this->cart, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

        return view('livewire.restaurant-checkout', [
            'subtotal' => $subtotal,
            'website' => $this->website,
        ]);
    }
}
