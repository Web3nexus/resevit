<?php

namespace App\Filament\Securegate\Resources\TenantResource\RelationManagers;

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

class BranchesRelationManager extends RelationManager
{
    protected static string $relationship = 'branches';

    protected static ?string $recordTitleAttribute = 'name';

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
                Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Components\Textarea::make('address')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
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
