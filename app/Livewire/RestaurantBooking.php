<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class RestaurantBooking extends Component
{
    public $website;

    public function mount()
    {
        $this->website = \App\Models\TenantWebsite::where('tenant_id', tenant('id'))->first();
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.restaurant-booking');
    }
}
