<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public Order $order;

    public $website;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->website = \App\Models\TenantWebsite::where('tenant_id', tenant('id'))->first();
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.order-confirmation', [
            'order' => $this->order,
            'website' => $this->website,
        ]);
    }
}
