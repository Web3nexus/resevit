<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\BusinessResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class BusinessResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationLabel = 'My Businesses';

    protected static ?string $modelLabel = 'Business';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return has_feature('multi_business');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('owner_user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Business Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Business Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Subdomain')
                            ->prefix('https://')
                            ->suffix('.' . parse_url(config('app.url'), PHP_URL_HOST))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('plan_id')
                            ->label('Pricing Plan')
                            ->options(\App\Models\PricingPlan::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->hiddenOn('create') // Hidden on create, auto-assigned
                            ->helperText('Plan is inherited from your main business.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('domain')
                    ->label('URL')
                    ->url(fn(Tenant $record) => 'https://' . $record->domain . '/dashboard/login', shouldOpenInNewTab: true)
                    ->color('primary'),
                Tables\Columns\TextColumn::make('plan.name')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\Action::make('dashboard')
                    ->label('Dashboard')
                    ->icon('heroicon-o-arrow-right-end-on-rectangle')
                    ->url(fn(Tenant $record) => 'https://' . $record->domain . '/dashboard/login', shouldOpenInNewTab: true),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
