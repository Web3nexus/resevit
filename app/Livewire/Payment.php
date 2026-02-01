<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\TenantWebsite;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Payment extends Component
{
    public Order $order;

    public $clientSecret;

    public $stripeKey;

    public $website;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->website = TenantWebsite::where('tenant_id', tenant('id'))->first();

        // In a real implementation, we would fetch the client secret from the payment intent
        // stored on the order (created in checkout)
        // $this->clientSecret = $order->payment_intent_client_secret;
        // For now we assume it was passed or stored on order

        $this->stripeKey = config('services.stripe.key');
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.payment', [
            'order' => $this->order,
            'website' => $this->website,
        ]);
    }
}
