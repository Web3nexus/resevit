<?php

namespace App\Filament\Dashboard\Pages;


use BackedEnum;
use UnitEnum;
use App\Models\PricingPlan;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Subscription extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected string $view = 'filament.dashboard.pages.subscription';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static int|null $navigationSort = 100;

    public $plans;
    public $currentPlan;
    public $billingCycle = 'monthly';

    public function mount()
    {
        $this->plans = PricingPlan::where('is_active', true)->orderBy('order')->get();
        $this->currentPlan = tenant() ? tenant()->plan : null;

        // Try to detect current interval from tenant if available
        if (tenant() && tenant()->subscription_interval) {
            $this->billingCycle = tenant()->subscription_interval;
        }
    }

    public function upgrade($planSlug)
    {
        $plan = PricingPlan::where('slug', $planSlug)->first();

        $priceId = $this->billingCycle === 'yearly' ? $plan->stripe_yearly_id : $plan->stripe_id;

        if (!$plan || !$priceId) {
            Notification::make()
                ->danger()
                ->title('Invalid Plan Configuration')
                ->body('The selected plan or billing cycle (' . $this->billingCycle . ') is not correctly configured for billing.')
                ->send();
            return;
        }

        try {
            return tenant()->checkout($priceId, [
                'success_url' => route('filament.dashboard.pages.subscription'),
                'cancel_url' => route('filament.dashboard.pages.subscription'),
            ]);
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Checkout Error')
                ->body($e->getMessage())
                ->send();
        }
    }

    public function manageBilling()
    {
        $tenant = tenant();

        if (!$tenant || !$tenant->stripe_id) {
            Notification::make()
                ->warning()
                ->title('No Billing History')
                ->body('You do not have an active billing history yet. Please subscribe to a plan first.')
                ->send();
            return;
        }

        try {
            return $tenant->redirectToBillingPortal(
                route('filament.dashboard.pages.subscription')
            );
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Billing Portal Error')
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manage_billing')
                ->label('Manage Billing')
                ->action('manageBilling')
                ->color('gray'),
        ];
    }
}
