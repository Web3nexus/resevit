<?php

namespace App\Filament\Dashboard\Resources;


use BackedEnum;
use UnitEnum;
use App\Filament\Dashboard\Resources\StaffResource\Pages;
use App\Models\Staff;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use App\Filament\Exports\StaffExporter;
use Filament\Schemas\Schema;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Staff Management';

    protected static int|null $navigationSort = 0;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return has_feature('staff');
    }

    public static function canCreate(): bool
    {
        $limit = get_feature_limit('staff');

        if ($limit === null)
            return true; // Unlimited

        return \App\Models\Staff::count() < $limit;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Staff Management');
    }

    public static function form(Schema $schema): Schema
    {
        $tenant = tenant();
        $enabledFeatures = app(\App\Services\FeatureManager::class)->getEnabledFeatures($tenant);
        $featureMapping = \App\Services\FeaturePermissionManager::getFeaturePermissions();

        $formSchema = [
            \Filament\Schemas\Components\Section::make('Profile Information')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Full Name')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(\App\Models\TenantUser::class, 'email', ignoreRecord: true),
                    \Filament\Forms\Components\TextInput::make('password')
                        ->password()
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->minLength(8)
                        ->dehydrated(fn(?string $state) => filled($state))
                        ->helperText('Leave blank to keep current password when editing'),
                    \Filament\Forms\Components\Select::make('position')
                        ->options([
                            'manager' => 'Manager',
                            'accountant' => 'Accountant',
                            'staff' => 'Staff',
                            'cashier' => 'Cashier',
                            'waiter' => 'Waiter',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if (!$state)
                                return;

                            // Map positions to role names
                            $roleName = match ($state) {
                                'manager' => 'manager',
                                'accountant' => 'accountant',
                                'cashier' => 'cashier',
                                'waiter' => 'waiter',
                                default => 'staff',
                            };

                            $role = \Spatie\Permission\Models\Role::where('name', $roleName)->first();
                            if ($role) {
                                $set('roles', [$role->id]);
                            }
                        }),
                    \Filament\Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('emergency_contact')
                        ->maxLength(255),
                    \Filament\Forms\Components\DatePicker::make('hire_date')
                        ->required()
                        ->default(now()),
                    \Filament\Forms\Components\TextInput::make('hourly_rate')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->required(),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'on_leave' => 'On Leave',
                        ])
                        ->default('active')
                        ->required(),
                    \Filament\Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->searchable()
                        ->preload()
                        ->default(fn() => \Illuminate\Support\Facades\Session::get('current_branch_id'))
                        ->required(),
                ]),

            \Filament\Schemas\Components\Section::make('Role & Permissions')
                ->description('Assign predefined roles or granular permissions based on enabled features.')
                ->columnSpanFull()
                ->schema([
                    \Filament\Forms\Components\Select::make('roles')
                        ->relationship('user.roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->live()
                        ->saveRelationshipsUsing(null)
                        ->afterStateUpdated(function ($state, $set, $get) use ($enabledFeatures, $featureMapping) {
                            if (empty($state))
                                return;

                            $rolePermissions = \Spatie\Permission\Models\Role::whereIn('id', $state)
                                ->with('permissions')
                                ->get()
                                ->pluck('permissions.*.id')
                                ->flatten()
                                ->unique()
                                ->toArray();

                            foreach ($enabledFeatures as $feature) {
                                if (isset($featureMapping[$feature])) {
                                    $featurePermIds = \Spatie\Permission\Models\Permission::whereIn('name', $featureMapping[$feature])
                                        ->pluck('id')
                                        ->toArray();

                                    $currentSelected = $get('feature_permissions_' . $feature) ?? [];
                                    // Merging role permissions with currently selected ones
                                    $newSelected = array_unique(array_merge($currentSelected, array_intersect($rolePermissions, $featurePermIds)));
                                    $set('feature_permissions_' . $feature, $newSelected);
                                }
                            }
                        }),

                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema(function () use ($enabledFeatures, $featureMapping) {
                            $permissionGrids = [];
                            foreach ($featureMapping as $feature => $permissions) {
                                if (in_array($feature, $enabledFeatures)) {
                                    $permissionGrids[] = \Filament\Schemas\Components\Fieldset::make(ucwords(str_replace('_', ' ', $feature)))
                                        ->schema([
                                            \Filament\Forms\Components\CheckboxList::make('feature_permissions_' . $feature)
                                                ->label('')
                                                ->options(function () use ($permissions) {
                                                    return \Spatie\Permission\Models\Permission::whereIn('name', $permissions)
                                                        ->pluck('name', 'id')
                                                        ->map(fn($p) => ucwords(str_replace(['_', 'manage'], [' ', ' Manage'], $p)));
                                                })
                                                ->afterStateHydrated(function ($component, $record) use ($permissions) {
                                                    if (!$record || !$record->user)
                                                        return;

                                                    // This can be slightly optimized by eager loading user.permissions
                                                    $userPermIds = $record->user->permissions->pluck('id')->toArray();
                                                    $featurePermIds = array_keys($component->getOptions());

                                                    $component->state(array_intersect($userPermIds, $featurePermIds));
                                                })
                                                ->dehydrated(true)
                                                ->columns(2)
                                                ->gridDirection('vertical'),
                                        ]);
                                }
                            }
                            return $permissionGrids;
                        }),
                ]),

            \Filament\Schemas\Components\Section::make('Availability')
                ->collapsed()
                ->columnSpanFull()
                ->schema([
                    \Filament\Forms\Components\KeyValue::make('availability')
                        ->label('Weekly Availability')
                        ->keyLabel('Day')
                        ->valueLabel('Hours')
                        ->helperText('Example: Monday => 9:00 AM - 5:00 PM'),
                ]),

            \Filament\Schemas\Components\Section::make('Bank Details')
                ->description('Staff banking information for payouts.')
                ->collapsed()
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    \Filament\Forms\Components\TextInput::make('bank_name')
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('account_holder_name')
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('account_number')
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('branch_code')
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('swift_bic')
                        ->label('SWIFT/BIC')
                        ->maxLength(255),
                ]),
        ];

        return $schema->schema($formSchema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable()
                    ->searchable(),
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
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('suspend')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'active')
                    ->action(fn($record) => $record->update(['status' => 'suspended'])),
                \Filament\Actions\Action::make('terminate')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn($record) => in_array($record->status, ['active', 'suspended']))
                    ->action(fn($record) => $record->update(['status' => 'terminated'])),
                \Filament\Actions\Action::make('reactivate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => in_array($record->status, ['suspended', 'terminated']))
                    ->action(fn($record) => $record->update(['status' => 'active'])),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(StaffExporter::class),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(StaffExporter::class),
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
