<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\OrderResource\Pages;
use App\Filament\Exports\OrderExporter;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string|UnitEnum|null $navigationGroup = 'Reservations'; // Or put in a new Sales/Orders group

    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return has_feature('pos');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Order Information')
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
                        Forms\Components\Select::make('staff_id')
                            ->relationship('staff.user', 'name')
                            ->label('Assigned Staff')
                            ->searchable()
                            ->preload()
                            ->placeholder('Assign to staff (optional)')
                            ->visible(fn () => auth()->user()->hasAnyRole(['owner', 'manager'])),
                        Forms\Components\Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => \Illuminate\Support\Facades\Session::get('current_branch_id'))
                            ->required()
                            ->label('Branch'),
                    ])->columns(2),

                Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('menu_item_display')
                                    ->label('Menu Item')
                                    ->formatStateUsing(fn ($record) => $record?->menuItem?->name)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('unit_price')
                                    ->prefix('$')
                                    ->disabled()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('variant')
                                    ->formatStateUsing(fn ($record) => $record?->variant?->name ?? '-')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('subtotal')
                                    ->prefix('$')
                                    ->disabled()
                                    ->columnSpan(1),
                            ])
                            ->columns(2)
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
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order #')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => 'ORD-'.str_pad($state, 6, '0', STR_PAD_LEFT)),
                Tables\Columns\TextColumn::make('order_source')
                    ->label('Source')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'pos' => 'info',
                        'phone' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Guest'),
                Tables\Columns\TextColumn::make('order_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'dine-in' => 'info',
                        'takeout' => 'warning',
                        'delivery' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->sortable()
                    ->default(fn ($record) => $record->total_amount),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'pending' => 'gray',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'preparing' => 'warning',
                        'ready' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('order_source')
                    ->label('Source')
                    ->options([
                        'online' => 'Online',
                        'pos' => 'POS',
                        'phone' => 'Phone',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'preparing' => 'Preparing',
                        'ready' => 'Ready',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('order_type')
                    ->label('Type')
                    ->options([
                        'dine-in' => 'Dine-in',
                        'takeout' => 'Takeout',
                        'delivery' => 'Delivery',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(OrderExporter::class),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exporter(OrderExporter::class),
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
