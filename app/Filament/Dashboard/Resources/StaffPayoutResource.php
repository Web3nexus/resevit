<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\StaffPayoutResource\Pages;
use App\Models\StaffPayout;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class StaffPayoutResource extends Resource
{
    protected static ?string $model = StaffPayout::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|\UnitEnum|null $navigationGroup = 'Staff Management';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return has_feature('staff');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('staff_id')
                    ->label('Staff Member')
                    ->options(function () {
                        return \App\Models\Staff::with('user')
                            ->get()
                            ->pluck('user.name', 'id');
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('hours_worked')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $staffId = $get('staff_id');
                        if ($staffId && $state) {
                            $staff = \App\Models\Staff::find($staffId);
                            if ($staff) {
                                $set('amount', $staff->hourly_rate * $state);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\DatePicker::make('payout_date')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('staff.user.name')
                    ->label('Staff Member')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours_worked')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payout_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('payout_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('payout_date', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('payout_date', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\Action::make('mark_as_paid')
                    ->action(function (StaffPayout $record) {
                        $record->update(['status' => 'paid', 'paid_at' => now()]);
                        Notification::make()
                            ->title('Payout marked as paid')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn(StaffPayout $record) => $record->status === 'pending'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('mark_as_paid')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->update(['status' => 'paid', 'paid_at' => now()]);
                            Notification::make()
                                ->title('Payouts marked as paid')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check-circle'),
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
            'index' => Pages\ListStaffPayouts::route('/'),
            'create' => Pages\CreateStaffPayout::route('/create'),
            'edit' => Pages\EditStaffPayout::route('/{record}/edit'),
        ];
    }
}
