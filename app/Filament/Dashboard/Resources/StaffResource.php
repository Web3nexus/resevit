<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\StaffResource\Pages;
use App\Models\Staff;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Staff Management';

    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return __('Staff Management');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(\App\Models\TenantUser::class, 'email', ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->dehydrated(fn(?string $state) => filled($state))
                    ->helperText('Leave blank to keep current password when editing'),
                Forms\Components\Select::make('position')
                    ->options([
                        'manager' => 'Manager',
                        'accountant' => 'Accountant',
                        'staff' => 'Staff',
                        'cashier' => 'Cashier',
                        'waiter' => 'Waiter',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('emergency_contact')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('hire_date')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('hourly_rate')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                    ])
                    ->default('active')
                    ->required(),
                Forms\Components\KeyValue::make('availability')
                    ->label('Weekly Availability')
                    ->keyLabel('Day')
                    ->valueLabel('Hours')
                    ->helperText('Example: Monday => 9:00 AM - 5:00 PM'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'manager' => 'success',
                        'accountant' => 'info',
                        'cashier' => 'warning',
                        'waiter' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'on_leave' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('hire_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hourly_rate')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Total Paid')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                    ]),
                Tables\Filters\SelectFilter::make('position')
                    ->options([
                        'manager' => 'Manager',
                        'accountant' => 'Accountant',
                        'staff' => 'Staff',
                        'cashier' => 'Cashier',
                        'waiter' => 'Waiter',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
