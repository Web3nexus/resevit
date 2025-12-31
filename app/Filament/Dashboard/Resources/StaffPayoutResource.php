<?php

namespace App\Filament\Dashboard\Resources;


use BackedEnum;
use UnitEnum;
use App\Filament\Dashboard\Resources\StaffPayoutResource\Pages;
use App\Models\StaffPayout;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Set;
use Filament\Forms\Get;

class StaffPayoutResource extends Resource
{
    protected static ?string $model = StaffPayout::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return has_feature('staff');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('staff_id')
                    ->label('Staff Member')
                    ->options(function () {
                        return \App\Models\Staff::with('user')
                            ->get()
                            ->pluck('user.name', 'id');
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('hours_worked')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $staffId = $get('staff_id');
                        if ($staffId && $state) {
                            $staff = \App\Models\Staff::find($staffId);
                            if ($staff) {
                                $set('amount', $staff->hourly_rate * $state);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\DatePicker::make('payout_date')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('staff.user.name')
                    ->label('Staff Member')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours_worked')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payout_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('payout_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('payout_date', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('payout_date', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\Action::make('mark_as_paid')
                    ->action(function (StaffPayout $record) {
                        $user = Auth::user();
                        $amount = $record->amount;

                        if ($user->wallet_balance < $amount) {
                            \Filament\Notifications\Notification::make()
                                ->title('Insufficient Balance')
                                ->body('Your wallet balance is not enough to complete this payout.')
                                ->danger()
                                ->send();
                            return;
                        }

                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $user, $amount) {
                            // 1. Deduct from wallet (Landlord)
                            $user->decrement('wallet_balance', (float) $amount);

                            // 2. Create Transaction (Landlord)
                            $transaction = \App\Models\Transaction::create([
                                'user_id' => $user->id,
                                'amount' => -$amount,
                                'type' => 'payout',
                                'status' => 'completed',
                                'description' => 'Staff Payout for ' . ($record->staff->user->name ?? 'Staff'),
                                'transactionable_type' => StaffPayout::class,
                                'transactionable_id' => $record->id,
                                'metadata' => ['staff_id' => $record->staff_id]
                            ]);

                            // 3. Update Payout record (Tenant)
                            $record->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                                'transaction_id' => $transaction->id
                            ]);
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Payout successful')
                            ->body('The amount has been deducted from your wallet.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn(StaffPayout $record) => $record->status === 'pending'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('mark_as_paid')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $user = Auth::user();
                            $totalAmount = $records->sum('amount');

                            if ($user->wallet_balance < $totalAmount) {
                                Notification::make()
                                    ->title('Insufficient Balance')
                                    ->body("Your wallet balance ($" . number_format((float) $user->wallet_balance, 2) . ") is not enough to complete these payouts (Total: $" . number_format((float) $totalAmount, 2) . ").")
                                    ->danger()
                                    ->send();
                                return;
                            }

                            DB::transaction(function () use ($records, $user, $totalAmount) {
                                // 1. Deduct total from wallet
                                $user->decrement('wallet_balance', $totalAmount);

                                // 2. Process each payout
                                foreach ($records as $record) {
                                    if ($record->status !== 'pending')
                                        continue;

                                    // Create Transaction for each
                                    $transaction = \App\Models\Transaction::create([
                                        'user_id' => $user->id,
                                        'amount' => -$record->amount,
                                        'type' => 'payout',
                                        'status' => 'completed',
                                        'description' => 'Staff Payout for ' . ($record->staff->user->name ?? 'Staff'),
                                        'transactionable_type' => StaffPayout::class,
                                        'transactionable_id' => $record->id,
                                        'metadata' => ['staff_id' => $record->staff_id, 'bulk' => true]
                                    ]);

                                    $record->update([
                                        'status' => 'paid',
                                        'paid_at' => now(),
                                        'transaction_id' => $transaction->id
                                    ]);
                                }
                            });

                            Notification::make()
                                ->title('Bulk payouts successful')
                                ->body('The total amount has been deducted from your wallet.')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check-circle'),
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
            'index' => Pages\ListStaffPayouts::route('/'),
            'create' => Pages\CreateStaffPayout::route('/create'),
            'edit' => Pages\EditStaffPayout::route('/{record}/edit'),
        ];
    }
}
