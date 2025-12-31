<?php

namespace App\Filament\Dashboard\Resources;


use BackedEnum;
use UnitEnum;
use App\Filament\Dashboard\Resources\RoleResource\Pages;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\PricingFeature;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|UnitEnum|null $navigationGroup = 'Staff Management';

    public static function canViewAny(): bool
    {
        return has_feature('staff');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Role Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('guard_name')
                            ->options([
                                'web' => 'Web',
                            ])
                            ->default('web')
                            ->required(),
                    ]),

                \Filament\Schemas\Components\Section::make('Feature Access')
                    ->description('Enable permissions for this role based on active features.')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->options(function () {
                                $tenant = tenant();
                                if (!$tenant)
                                    return [];

                                $enabledFeatures = app(\App\Services\FeatureManager::class)->getEnabledFeatures($tenant);
                                $mapping = \App\Services\FeaturePermissionManager::getFeaturePermissions();

                                $allowedPermissionNames = [];
                                foreach ($enabledFeatures as $feature) {
                                    if (isset($mapping[$feature])) {
                                        $allowedPermissionNames = array_merge($allowedPermissionNames, $mapping[$feature]);
                                    }
                                }

                                return \Spatie\Permission\Models\Permission::whereIn('name', $allowedPermissionNames)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2),
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guard_name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
