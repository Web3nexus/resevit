<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderStatus extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.order-status')
            ->layout('components.layouts.app');
    }
}
