<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Table;
use App\Models\Variant;
use Filament\Notifications\Notification;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PosTerminal extends Component
{
    public $categories;
    public $selectedCategory = null;
    public $menuItems = [];
    public $cart = [];
    public $selectedTable = null;
    public $tables;
    public $paymentMethod = 'cash';
    public $amountReceived = 0;
    public $selectedStaff = null;
    public $staffMembers;

    public function mount()
    {
        $this->categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        if ($this->categories->isNotEmpty()) {
            $this->selectCategory($this->categories->first()->id);
        }
        $this->tables = Table::all();
        $this->staffMembers = \App\Models\Staff::with('user')->get(); // Get tenant staff
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->menuItems = MenuItem::where('category_id', $categoryId)
            ->where('is_available', true)
            ->with('variants')
            ->get();
    }

    public function addToCart($menuItemId, $variantId = null)
    {
        $item = MenuItem::find($menuItemId);
        if (!$item) return;

        $price = $item->base_price;
        $variantName = null;

        if ($variantId) {
            $variant = Variant::find($variantId);
            if ($variant) {
                $price += $variant->price_adjustment;
                $variantName = $variant->name;
            }
        }

        $cartKey = $menuItemId . '-' . ($variantId ?? 'novar');

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity']++;
            $this->cart[$cartKey]['subtotal'] = $this->cart[$cartKey]['quantity'] * $this->cart[$cartKey]['price'];
        } else {
            $this->cart[$cartKey] = [
                'menu_item_id' => $menuItemId,
                'variant_id' => $variantId,
                'name' => $item->name,
                'variant_name' => $variantName,
                'price' => $price,
                'quantity' => 1,
                'subtotal' => $price,
            ];
        }
    }

    public function removeFromCart($cartKey)
    {
        if (isset($this->cart[$cartKey])) {
            unset($this->cart[$cartKey]);
        }
    }

    public function updateQuantity($cartKey, $quantity)
    {
        if (isset($this->cart[$cartKey]) && $quantity > 0) {
            $this->cart[$cartKey]['quantity'] = $quantity;
            $this->cart[$cartKey]['subtotal'] = $quantity * $this->cart[$cartKey]['price'];
        }
    }

    public function getTotalProperty()
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }
    
    public function getChangeProperty()
    {
        return max(0, $this->amountReceived - $this->getTotalProperty());
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            Notification::make()->title('Cart is empty')->warning()->send();
            return;
        }

        $order = Order::create([
            'table_id' => $this->selectedTable,
            'staff_id' => $this->selectedStaff,
            'total_amount' => $this->total,
            'status' => 'completed', // Or preparing
            'payment_status' => 'paid',
            'type' => 'dine-in', // Could be dynamic
        ]);

        foreach ($this->cart as $item) {
            $order->items()->create([
                'menu_item_id' => $item['menu_item_id'],
                'variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        $order->payments()->create([
            'amount' => $this->total,
            'payment_method' => $this->paymentMethod,
            'status' => 'completed',
        ]);

        // Clear state but keep order ID to print receipt?
        // Or redirect to special success page?
        // For now just notify.
        
        $this->reset(['cart', 'selectedTable', 'amountReceived', 'selectedStaff']);
        
        // Dispatch event to open print modal or redirect
        $this->dispatch('order-completed', orderId: $order->id);
        
        // Open receipt in new tab via JS
        $this->js("window.open('" . route('dashboard.pos.receipt', $order) . "', '_blank')");

        Notification::make()->title('Order placed successfully!')->success()->send();
    }
    
    public function printReceipt($orderId)
    {
        $this->js("window.open('" . route('dashboard.pos.receipt', $orderId) . "', '_blank')");
    }

    public function render()
    {
        return view('livewire.pos-terminal');
    }
}
