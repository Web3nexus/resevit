<?php

namespace App\Filament\Dashboard\Pages;


use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use App\Models\Room;
use Filament\Actions\Action;

class LiveMonitor extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected string $view = 'filament.dashboard.pages.live-monitor';

    protected static ?string $navigationLabel = 'Live Floor View';

    protected static ?string $title = 'Live Floor Monitor';

    protected static string | UnitEnum | null $navigationGroup = 'Reservations';

    public static function canAccess(): bool
    {
        return has_feature('live_monitoring');
    }

    public $selectedRoomId;
    public $tables = [];
    public $statuses = [];

    public function mount()
    {
        $this->selectedRoomId = Room::first()?->id;
        $this->loadRoomData();
    }

    public function updatedSelectedRoomId()
    {
        $this->loadRoomData();
    }

    public function loadRoomData()
    {
        if (!$this->selectedRoomId) {
            $this->tables = [];
            $this->statuses = [];
            return;
        }

        $room = Room::find($this->selectedRoomId);
        if (!$room) {
            $this->tables = [];
            $this->statuses = [];
            return;
        }

        $this->tables = $room->tables()
            ->get()
            ->map(function ($table) {
                return [
                    'id' => $table->id,
                    'name' => $table->name,
                    'status' => $table->status,
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

        $this->statuses = collect($this->tables)->pluck('status', 'id')->toArray();
    }

    public function refreshStatuses()
    {
        if (!$this->selectedRoomId)
            return;

        // Fetch just statuses to avoid overwriting layout changes during drag
        $this->statuses = \App\Models\Table::where('room_id', $this->selectedRoomId)
            ->pluck('status', 'id')
            ->toArray();
    }

    public function saveLayout($updatedTables)
    {
        foreach ($updatedTables as $t) {
            if (isset($t['id'])) {
                \App\Models\Table::where('id', $t['id'])->update([
                    'x' => $t['x'],
                    'y' => $t['y'],
                ]);
            }
        }

        \Filament\Notifications\Notification::make()
            ->title('Layout updated')
            ->success()
            ->send();

        $this->loadRoomData();
    }

    public function getRoomsProperty()
    {
        return Room::all();
    }
}
