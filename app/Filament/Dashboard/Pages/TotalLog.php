<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class TotalLog extends Page
{

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Total Log';

    protected static ?string $title = 'Activity Across All Businesses';

    protected string $view = 'filament.dashboard.pages.total-log';

    protected static \UnitEnum|string|null $navigationGroup = 'System';

    public static function canAccess(): bool
    {
        return has_feature('audit_logs');
    }

    public $logs = [];

    public function mount()
    {
        $this->logs = $this->getLogs();
    }

    protected function getLogs(): Collection
    {
        $logs = collect();
        $user = auth()->user();

        // Loop through all owned tenants
        $tenants = $user->tenants;

        foreach ($tenants as $tenant) {
            try {
                $tenant->run(function () use (&$logs, $tenant) {
                    // Fetch latest 10 logs from this tenant
                    $tenantLogs = Activity::with('causer')->latest()->take(10)->get();

                    // decorate with tenant name
                    $tenantLogs->transform(function ($log) use ($tenant) {
                        $log->tenant_name = $tenant->name;
                        return $log;
                    });

                    $logs = $logs->merge($tenantLogs);
                });
            } catch (\Exception $e) {
                // Ignore connection errors for suspended/deleted tenants
            }
        }

        // Sort merged logs by date descending and take top 50
        return $logs->sortByDesc('created_at')->take(50)->values();
    }
}
