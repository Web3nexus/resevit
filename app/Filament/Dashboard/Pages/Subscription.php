<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\PricingPlan;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Subscription extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected string $view = 'filament.dashboard.pages.subscription';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static int|null $navigationSort = 100;

    public $plans;
    public $currentPlan;

    public function mount()
    {
        $this->plans = PricingPlan::where('is_active', true)->orderBy('order')->get();
        $this->currentPlan = tenant()->plan;
    }

    public function upgrade($planSlug)
    {
        $plan = PricingPlan::where('slug', $planSlug)->first();

        if (!$plan || !$plan->stripe_id) {
            Notification::make()
                ->danger()
                ->title('Invalid Plan')
                ->body('The selected plan is not correctly configured for billing.')
                ->send();
            return;
        }

        try {
            return tenant()->checkout($plan->stripe_id, [
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manage_billing')
                ->label('Manage Billing')
                ->url('#') // Link to Stripe Customer Portal in future
                ->color('gray'),
        ];
    }
}
