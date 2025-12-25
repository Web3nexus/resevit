<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Session;

class RestaurantMenu extends Component
{
    public $activeCategoryId = null;
    public $cart = [];
    public $isCartOpen = false;

    public function mount()
    {
        $this->cart = Session::get('cart', []);

        // Default to first category
        $firstCategory = Category::where('is_active', true)->orderBy('sort_order')->first();
        if ($firstCategory) {
            $this->activeCategoryId = $firstCategory->id;
        }
    }

    public function updatedActiveCategoryId()
    {
        // Scroll to category logic could be handled in frontend
    }

    public function addToCart($menuItemId, $variantId = null, $quantity = 1, $addons = [])
    {
        $item = MenuItem::find($menuItemId);
        if (!$item) return;

        $cartItem = [
            'id' => uniqid(), // Unique cart item ID
            'menu_item_id' => $item->id,
            'name' => $item->name,
            'price' => $item->base_price, // Needs variant adjustment
            'quantity' => $quantity,
            'variant_id' => $variantId,
            'addons' => $addons,
            // 'image' => $item->image_path,
        ];

        // Basic implementation for now
        $this->cart[] = $cartItem;
        $this->saveCart();

        $this->dispatch('cart-updated');
        $this->isCartOpen = true;
    }

    public function removeFromCart($cartItemId)
    {
        $this->cart = array_filter($this->cart, fn($item) => $item['id'] !== $cartItemId);
        $this->saveCart();
    }

    protected function saveCart()
    {
        Session::put('cart', $this->cart);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $categories = Category::where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_active', true)->where('is_available', true)->with('variants');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('livewire.restaurant-menu', [
            'categories' => $categories,
        ]); // Use a guest layout
    }
}
