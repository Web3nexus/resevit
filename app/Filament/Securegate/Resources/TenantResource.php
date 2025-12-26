<?php

namespace App\Filament\Securegate\Resources;

use App\Filament\Securegate\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationLabel = 'Businesses';

    protected static ?string $modelLabel = 'Business';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = 'Internal Users';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('domain')
                            ->maxLength(255),
                        Forms\Components\Select::make('owner_user_id')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Owner'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Subscription & Plan')
                    ->schema([
                        Forms\Components\Select::make('plan_id')
                            ->relationship('plan', 'name')
                            ->required()
                            ->label('Pricing Plan'),
                        Forms\Components\DateTimePicker::make('trial_ends_at')
                            ->label('Trial Ends At'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'suspended' => 'Suspended',
                                'pending' => 'Pending',
                            ])
                            ->required()
                            ->default('active'),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make('Location & Settings')
                    ->schema([
                        Forms\Components\TextInput::make('mobile')
                            ->tel(),
                        Forms\Components\TextInput::make('country'),
                        Forms\Components\TextInput::make('timezone'),
                        Forms\Components\TextInput::make('currency'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Directory & SEO')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('is_public')
                                    ->label('Public Listing')
                                    ->default(true),
                                Forms\Components\Toggle::make('is_sponsored')
                                    ->label('Sponsored/Ad'),
                                Forms\Components\TextInput::make('sponsored_ranking')
                                    ->label('Ad Ranking Score')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Higher score appears first'),
                            ]),
                        Forms\Components\Select::make('business_category_id')
                            ->label('Directory Category')
                            ->relationship('businessCategory', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\FileUpload::make('cover_image')
                            ->image()
                            ->directory('businesses/covers'),
                        Forms\Components\Textarea::make('description')
                            ->label('Directory Description')
                            ->rows(3),
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title'),
                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->rows(2),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Business Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('plan')
                    ->relationship('plan', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'view' => Pages\ViewTenant::route('/{record}'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
