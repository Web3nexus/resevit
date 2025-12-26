<?php

namespace App\Filament\Dashboard\Resources\BusinessResource\Pages;

use App\Filament\Dashboard\Resources\BusinessResource;
use App\Models\PricingPlan;
use App\Models\Tenant;
use App\Services\TenantCreatorService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = auth()->user();
        $tenants = $user->tenants()->with('plan')->get();

        // 1. Determine current limit and best plan
        $maxAllowed = 1; // Default
        $bestPlan = null;

        foreach ($tenants as $tenant) {
            // Check for 'multi_business' feature limit by accessing the relationship directly
            // or using the helper on the tenant
            $limit = get_feature_limit('multi_business', $tenant);

            if ($limit && $limit > $maxAllowed) {
                $maxAllowed = $limit;
                $bestPlan = $tenant->plan;
            }
            // Fallback: if we just have a plan and no specific limit found (maybe unlimited or not set),
            // we keep checking. If limit is null/0, it might mean disabled or unlimited.
            // Assuming 1 as default base.

            // Also keep track of a plan even if it doesn't give extra limits, for inheritance
            if (!$bestPlan && $tenant->plan) {
                $bestPlan = $tenant->plan;
            }
        }

        // 2. Check Limits
        if ($tenants->count() >= $maxAllowed) {
            \Filament\Notifications\Notification::make()
                ->title('Business Limit Reached')
                ->body("Your current plan allows for a maximum of {$maxAllowed} businesses. Please upgrade your plan to create more.")
                ->danger()
                ->send();

            $this->halt();
        }

        // 3. Prepare data
        // Inherit plan from the best tenant, or default if none found
        $planId = $bestPlan?->id ?? PricingPlan::where('slug', 'starter')->value('id');

        // No trial needed if we are just creating an additional slot (it follows main plan validity usually)
        // But code asks for trial days if plan has them.
        // If we piggyback, we might not give a free trial if the main account is past trial? 
        // For simplicity V1: Just assign the plan.

        $creator = new TenantCreatorService();

        return $creator->createTenant(
            $user,
            $data['name'],
            $data['slug'],
            [], // Extra data
            $planId,
            null // No payment method -> Is Included / Cardless
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
