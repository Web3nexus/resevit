<?php

namespace App\Filament\Securegate\Resources\TenantResource\RelationManagers;

use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Forms\Components;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;

class StaffRelationManager extends RelationManager
{
    protected static string $relationship = 'staff';

    protected static ?string $recordTitleAttribute = 'position';

    public function form(Schema $schema): Schema
    {
        // Ensure tenancy is initialized for the correct database
        $tenant = $this->getOwnerRecord();
        $dbName = config('tenancy.database.prefix') . $tenant->id . config('tenancy.database.suffix');
        $dbExists = count(\Illuminate\Support\Facades\DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName])) > 0;

        if ($dbExists && (!tenancy()->initialized || tenant('id') !== $tenant->id)) {
            tenancy()->initialize($tenant);
        }

        return $schema
            ->schema([
                Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Components\Select::make('branch_id')
                    ->label('Branch')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->nullable(),
                Components\TextInput::make('position')
                    ->required()
                    ->maxLength(255),
                Components\DatePicker::make('hire_date')
                    ->required()
                    ->default(now()),
                Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                        'suspended' => 'Suspended',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        // Ensure tenancy is initialized for the correct database
        $tenant = $this->getOwnerRecord();
        $dbName = config('tenancy.database.prefix') . $tenant->id . config('tenancy.database.suffix');
        $dbExists = count(\Illuminate\Support\Facades\DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName])) > 0;

        if ($dbExists && (!tenancy()->initialized || tenant('id') !== $tenant->id)) {
            tenancy()->initialize($tenant);
        }

        if (!$dbExists) {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('status')
                        ->default('Database Missing')
                        ->badge()
                        ->color('danger'),
                ])
                ->emptyStateHeading('Database Missing')
                ->emptyStateDescription('The database for this tenant does not exist. Please recreate it from the tenant settings.');
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'on_leave' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
