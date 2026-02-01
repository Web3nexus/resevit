<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Reservation;
use App\Services\TableOptimizationService;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

class TableOptimization extends Page implements \Filament\Schemas\Contracts\HasSchemas
{
    use \Filament\Schemas\Concerns\InteractsWithSchemas;

    protected string $view = 'filament.dashboard.pages.table-optimization';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    public ?array $optimizationData = [];

    public array $suggestions = [];

    public bool $isOptimizing = false;

    protected function getSchemas(): array
    {
        return ['optimizationForm'];
    }

    public static function canAccess(): bool
    {
        return has_feature('ai_optimization');
    }

    public function mount()
    {
        $this->optimizationData = [
            'date' => now()->format('Y-m-d'),
        ];
    }

    public function optimizationForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                DatePicker::make('date')
                    ->label('Select Date')
                    ->required()
                    ->live(),
            ])
            ->statePath('optimizationData');
    }

    public function runOptimization(TableOptimizationService $service)
    {
        $this->validate();
        $this->isOptimizing = true;

        try {
            $results = $service->optimize($this->optimizationData['date']);

            if (isset($results['message'])) {
                Notification::make()->warning()->title($results['message'])->send();
                $this->suggestions = [];
            } else {
                $this->suggestions = $results;
                Notification::make()->success()->title('Optimization complete!')->send();
            }
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Error')->body($e->getMessage())->send();
        } finally {
            $this->isOptimizing = false;
        }
    }

    public function applySuggestions()
    {
        foreach ($this->suggestions as $resId => $tableId) {
            Reservation::where('id', $resId)->update(['table_id' => $tableId]);
        }

        Notification::make()->success()->title('Suggestions applied!')->send();
        $this->suggestions = [];
    }

    public function getReservationsProperty()
    {
        return Reservation::whereDate('reservation_time', $this->optimizationData['date'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('table')
            ->get();
    }
}
