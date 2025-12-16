<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string | \UnitEnum | null $navigationGroup = 'Reservations'; // Or put in a new Sales/Orders group

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Order Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'preparing' => 'Preparing',
                                'ready' => 'Ready',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'dine-in' => 'Dine-in',
                                'pickup' => 'Pickup',
                                'delivery' => 'Delivery',
                            ])
                            ->required(),
                        Forms\Components\Select::make('table_id')
                            ->relationship('table', 'name')
                            ->label('Table'),
                    ])->columns(2),

                Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('menu_item_id')
                                    ->relationship('menuItem', 'name')
                                    ->disabled()
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->disabled(),
                                Forms\Components\TextInput::make('unit_price')
                                    ->prefix('$')
                                    ->disabled(),
                                Forms\Components\Placeholder::make('variant')
                                    ->content(fn($record) => $record?->variant?->name ?? '-'),
                                Forms\Components\TextInput::make('subtotal')
                                    ->prefix('$')
                                    ->disabled(),
                            ])
                            ->columns(5)
                            ->addable(false)
                            ->deletable(false),
                    ]),

                Section::make('Totals')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->prefix('$')
                            ->disabled(),
                        Forms\Components\Textarea::make('notes')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('table.name')
                    ->label('Table')
                    ->placeholder('N/A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'dine-in' => 'info',
                        'pickup' => 'warning',
                        'delivery' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'preparing' => 'warning',
                        'ready' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'preparing' => 'Preparing',
                        'ready' => 'Ready',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
