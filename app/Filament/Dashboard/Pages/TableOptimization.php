<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Reservation;
use App\Models\Table;
use App\Services\TableOptimizationService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class TableOptimization extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.dashboard.pages.table-optimization';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    public ?string $date = null;
    public array $suggestions = [];
    public bool $isOptimizing = false;

    public static function canAccess(): bool
    {
        return has_feature('ai_optimization');
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->form->fill(['date' => $this->date]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                DatePicker::make('date')
                    ->label('Select Date')
                    ->required()
                    ->live(),
            ]);
    }

    public function runOptimization(TableOptimizationService $service)
    {
        $this->validate();
        $this->isOptimizing = true;

        try {
            $results = $service->optimize($this->date);

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
        return Reservation::whereDate('reservation_time', $this->date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('table')
            ->get();
    }
}
