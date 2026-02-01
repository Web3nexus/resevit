<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

class RestaurantMenu extends Component
{
    public $website;

    public $activeCategoryId = null;

    public $cart = [];

    public $isCartOpen = false;

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        $this->website = \App\Models\TenantWebsite::where('tenant_id', tenant('id'))->first();

        if (! $this->website) {
            // Fallback to a default structure if no website settings exist
            $this->website = new \App\Models\TenantWebsite([
                'content' => [
                    'business_name' => tenant('name'),
                    'settings' => [],
                ],
            ]);
        }

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
        $this->dispatch('add-to-cart', [
            'menuItemId' => $menuItemId,
            'variantId' => $variantId,
            'quantity' => $quantity,
            'addons' => $addons,
        ]);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $categories = Category::where('is_active', true)
            ->with([
                'menuItems' => function ($query) {
                    $query->where('is_active', true)->where('is_available', true)->with('variants');
                },
            ])
            ->orderBy('sort_order')
            ->get();

        return view('livewire.restaurant-menu', [
            'categories' => $categories,
            'website' => $this->website,
        ]); // Use a guest layout
    }
}
