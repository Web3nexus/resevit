<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;
use Filament\Schemas\Schema;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Reservations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Main Dining Hall'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(500)
                    ->placeholder('Optional description of the area'),
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('tables_count')
                    ->counts('tables')
                    ->label('Tables'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\Action::make('design')
                    ->label('Floor Plan')
                    ->icon('heroicon-o-squares-2x2')
                    ->url(fn(Room $record): string => RoomResource::getUrl('floor-plan', ['record' => $record])),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
            'floor-plan' => Pages\EditFloorPlan::route('/{record}/floor-plan'),
        ];
    }
}
