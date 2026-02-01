<?php

namespace App\Livewire;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class CartManager extends Component
{
    public $cart = [];

    public $isCartOpen = false;

    public function mount()
    {
        $this->cart = Session::get('cart', []);
    }

    #[On('add-to-cart')]
    public function addToCart($menuItemId, $quantity = 1, $variantId = null, $addons = [])
    {
        $item = MenuItem::find($menuItemId);
        if (! $item) {
            return;
        }

        $cartItem = [
            'id' => uniqid(),
            'menu_item_id' => $item->id,
            'name' => $item->name,
            'price' => (float) $item->base_price,
            'quantity' => $quantity,
            'variant_id' => $variantId,
            'addons' => $addons,
            'image' => $item->image_path,
        ];

        $this->cart[] = $cartItem;
        $this->saveCart();
        $this->isCartOpen = true;
    }

    public function removeFromCart($cartItemId)
    {
        $this->cart = array_filter($this->cart, fn ($item) => $item['id'] !== $cartItemId);
        $this->saveCart();
    }

    public function updateQuantity($cartItemId, $change)
    {
        foreach ($this->cart as &$item) {
            if ($item['id'] === $cartItemId) {
                $item['quantity'] = max(1, $item['quantity'] + $change);
                break;
            }
        }
        $this->saveCart();
    }

    protected function saveCart()
    {
        Session::put('cart', $this->cart);
    }

    public function getSubtotalProperty()
    {
        return array_reduce($this->cart, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);
    }

    public function render()
    {
        return view('livewire.cart-manager', [
            'subtotal' => $this->subtotal,
        ]);
    }
}
