<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

class OrderStatus extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.order-status');
    }
}
