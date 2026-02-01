<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\MenuItem;
use App\Models\ReservationSetting;
use App\Models\TenantWebsite;
use Filament\Widgets\Widget;

class SetupProgressWidget extends Widget
{
    protected string $view = 'filament.dashboard.widgets.setup-progress-widget';

    protected int|string|array $columnSpan = 'full';

    public int $progress = 0;

    public array $steps = [];

    public function mount()
    {
        $this->calculateProgress();
    }

    public function calculateProgress()
    {
        $settings = ReservationSetting::getInstance();
        $hasProfile = ! empty($settings->business_name) && ! empty($settings->business_address);
        $hasMenu = MenuItem::count() > 0;
        $hasWebsite = TenantWebsite::where('tenant_id', tenant('id'))->exists();
        $isPublished = TenantWebsite::where('tenant_id', tenant('id'))->where('is_published', true)->exists();

        $steps = [
            [
                'label' => 'Complete Business Profile',
                'description' => 'Add your business name, address, and logo.',
                'completed' => $hasProfile,
                'action' => route('filament.dashboard.pages.edit-profile'),
                'icon' => 'heroicon-o-building-storefront',
            ],
            [
                'label' => 'Create Your Menu',
                'description' => 'Add items to your digital menu.',
                'completed' => $hasMenu,
                'action' => route('filament.dashboard.resources.menu-items.index'), // Verify route
                'icon' => 'heroicon-o-book-open',
            ],
            [
                'label' => 'Select Website Template',
                'description' => 'Choose a design for your site.',
                'completed' => $hasWebsite,
                'action' => route('filament.dashboard.pages.website-builder'),
                'icon' => 'heroicon-o-computer-desktop',
            ],
            [
                'label' => 'Publish Website',
                'description' => 'Make your website live.',
                'completed' => $isPublished,
                'action' => route('filament.dashboard.pages.website-builder'),
                'icon' => 'heroicon-o-rocket-launch',
            ],
        ];

        $completedCount = collect($steps)->filter(fn ($step) => $step['completed'])->count();
        $this->progress = round(($completedCount / count($steps)) * 100);
        $this->steps = $steps;
    }
}
