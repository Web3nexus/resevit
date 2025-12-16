<?php

namespace App\Filament\Dashboard\Resources\RoomResource\Pages;

use App\Filament\Dashboard\Resources\RoomResource;
use App\Models\Room;
use Filament\Resources\Pages\Page;
use App\Models\Table;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class EditFloorPlan extends Page
{
    protected static string $resource = RoomResource::class;

    protected string $view = 'filament.dashboard.resources.room-resource.pages.edit-floor-plan';

    public Room $record;

    public $tables = [];

    public function mount(Room $record): void
    {
        $this->record = $record;
        $this->refreshTables();
    }

    public function refreshTables()
    {
        $this->tables = $this->record->tables()
            ->get()
            ->map(function ($table) {
                return [
                    'id' => $table->id,
                    'name' => $table->name,
                    'is_new' => false,
                    'x' => $table->x ?? 0,
                    'y' => $table->y ?? 0,
                    'width' => $table->width ?? 100,
                    'height' => $table->height ?? 100,
                    'shape' => $table->shape ?? 'rect',
                    'rotation' => $table->rotation ?? 0,
                    'seats' => $table->capacity,
                ];
            })
            ->toArray();
    }

    public function saveLayout($updatedTables)
    {
        foreach ($updatedTables as $t) {
            if (isset($t['id']) && !$t['is_new']) {
                Table::where('id', $t['id'])->update([
                    'x' => $t['x'],
                    'y' => $t['y'],
                    'width' => $t['width'],
                    'height' => $t['height'],
                    'rotation' => $t['rotation'] ?? 0,
                ]);
            }
        }

        Notification::make()
            ->title('Floor plan saved successfully')
            ->success()
            ->send();

        $this->refreshTables();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_table')
                ->label('Add Table')
                ->icon('heroicon-o-plus')
                ->form([
                    \Filament\Forms\Components\TextInput::make('name')->required(),
                    \Filament\Forms\Components\TextInput::make('capacity')->numeric()->default(4)->required(),
                    \Filament\Forms\Components\Select::make('shape')
                        ->options([
                            'rect' => 'Rectangle',
                            'circle' => 'Circle',
                        ])->default('rect'),
                ])
                ->action(function (array $data) {
                    $this->record->tables()->create([
                        'name' => $data['name'],
                        'capacity' => $data['capacity'],
                        'shape' => $data['shape'],
                        'x' => 50,
                        'y' => 50,
                        'width' => 100, // default dimensions
                        'height' => 100,
                    ]);

                    Notification::make()->title('Table created')->success()->send();
                    $this->refreshTables();
                }),
        ];
    }
}
