<?php

namespace App\Filament\Securegate\Resources;

use App\Filament\Securegate\Resources\InvestorResource\Pages;
use App\Models\Investor;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Schema;

class InvestorResource extends Resource
{
    protected static ?string $model = Investor::class;

    protected static ?string $navigationLabel = 'Investors';
    
    protected static ?string $modelLabel = 'Investor';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    
    protected static string|\UnitEnum|null $navigationGroup = 'External Users';
    
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Full Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('wallet_balance')
                    ->numeric()
                    ->default(0)
                    ->prefix('$')
                    ->maxValue(999999999.99)
                    ->step(0.01),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('user_type')
                    ->label('User Type')
                    ->badge()
                    ->color('warning')
                    ->default('Investor'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('wallet_balance')
                    ->label('Wallet Balance')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (string $state): string {
                        return match ($state) {
                            'Active' => 'success',
                            'Suspended' => 'warning',
                            'Deleted' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->default('Active')
                    ->getStateUsing(function ($record) {
                        // Derive status - investors don't have soft deletes in current schema
                        // but we can add logic here if needed
                        return 'Active';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Row click will navigate to view/edit
            ])
            ->bulkActions([
                // Bulk actions can be added here
            ])
            ->emptyStateHeading('No investors yet')
            ->emptyStateDescription('Investors will appear here once they register on the platform.')
            ->emptyStateIcon('heroicon-o-banknotes');
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
            'index' => Pages\ListInvestors::route('/'),
            'create' => Pages\CreateInvestor::route('/create'),
            'view' => Pages\ViewInvestor::route('/{record}'),
            'edit' => Pages\EditInvestor::route('/{record}/edit'),
        ];
    }
}
