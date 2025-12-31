<?php

namespace App\Filament\Securegate\Resources;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Schema;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $modelLabel = 'Customer';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | UnitEnum | null $navigationGroup = 'External Users';

    protected static ?int $navigationSort = 1;

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
                Forms\Components\Textarea::make('address')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
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
                    ->color('info')
                    ->default('Customer'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
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
                        // Derive status from soft deletes if available, otherwise fall back to checking deleted_at
                        try {
                            if (method_exists($record, 'trashed') && $record->trashed()) {
                                return 'Deleted';
                            }
                        } catch (\Throwable $_) {
                            // ignore and fall through to attribute check
                        }

                        $deletedAt = $record->getAttribute('deleted_at');
                        if (!empty($deletedAt)) {
                            return 'Deleted';
                        }

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
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('addFunds')
                    ->label('Add Funds')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->minValue(0.01),
                    ])
                    ->action(function (Customer $record, array $data) {
                        if (isset($record->wallet_balance)) {
                            $record->increment('wallet_balance', $data['amount']);

                            \Filament\Notifications\Notification::make()
                                ->title('Funds Added Successfully')
                                ->body("Added $" . number_format($data['amount'], 2) . " to {$record->name}'s wallet.")
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Action Unavailable')
                                ->body("This user type does not currently have a wallet.")
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Bulk actions can be added here
            ])
            ->emptyStateHeading('No customers yet')
            ->emptyStateDescription('Customers will appear here once they register on the platform.')
            ->emptyStateIcon('heroicon-o-user-group');
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
