<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Reservations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('guest_name')
                    ->label('Guest Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('guest_email')
                    ->label('Guest Email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('guest_phone')
                    ->label('Guest Phone')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('party_size')
                    ->label('Party Size')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100),
                Forms\Components\DateTimePicker::make('reservation_time')
                    ->label('Reservation Time')
                    ->required()
                    ->seconds(false)
                    ->native(false),
                Forms\Components\TextInput::make('duration_minutes')
                    ->label('Duration (minutes)')
                    ->required()
                    ->numeric()
                    ->minValue(30)
                    ->maxValue(480)
                    ->default(fn() => \App\Models\ReservationSetting::getInstance()->default_duration_minutes)
                    ->helperText('How long the reservation will last'),
                Forms\Components\Select::make('table_id')
                    ->label('Table')
                    ->relationship('table', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Assign a table (optional)'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'seated' => 'Seated',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Textarea::make('special_requests')
                    ->label('Special Requests')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guest_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reservation_time')
                    ->label('Start Time')
                    ->dateTime()
                    ->sortable()
                    ->description(fn(Reservation $record) => $record->reservation_time->diffForHumans()),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time')
                    ->dateTime()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn($state) => $state . ' min')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('party_size')
                    ->numeric()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('table.name')
                    ->placeholder('Unassigned')
                    ->sortable(),
                Tables\Columns\TextColumn::make('confirmation_code')
                    ->label('Code')
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'info',
                        'seated' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'primary',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('today')
                    ->query(fn($query) => $query->whereDate('reservation_time', today()))
                    ->label('Today'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'seated' => 'Seated',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('confirm')
                    ->action(fn(Reservation $record) => $record->update(['status' => 'confirmed']))
                    ->requiresConfirmation()
                    ->color('info')
                    ->visible(fn(Reservation $record) => $record->status === 'pending')
                    ->icon('heroicon-o-check'),
                \Filament\Actions\Action::make('seat')
                    ->action(fn(Reservation $record) => $record->update(['status' => 'seated']))
                    ->requiresConfirmation()
                    ->color('success')
                    ->visible(fn(Reservation $record) => $record->status === 'confirmed')
                    ->icon('heroicon-o-arrow-right-end-on-rectangle'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('reservation_time', 'asc');
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
