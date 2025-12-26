<?php

namespace App\Filament\Securegate\Resources;

use App\Filament\Securegate\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Schema;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Business Owners';

    protected static ?string $modelLabel = 'Business Owner';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static string|\UnitEnum|null $navigationGroup = 'Internal Users';

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
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name', fn($query) => $query->where('guard_name', 'web'))
                    ->preload(),
                Forms\Components\TextInput::make('wallet_balance')
                    ->label('Wallet Balance')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->helperText('Available balance for promotions and other services.'),
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
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('wallet_balance')
                    ->label('Wallet Balance')
                    ->money('USD')
                    ->sortable()
                    ->color('success'),
                Tables\Columns\TextColumn::make('user_type')
                    ->label('User Type')
                    ->badge()
                    ->color('primary')
                    ->default('Business Owner'),
                Tables\Columns\TextColumn::make('tenants_count')
                    ->label('Tenants')
                    ->counts('tenants')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('tenants.name')
                    ->label('Business Names')
                    ->badge()
                    ->separator(',')
                    ->limitList(3)
                    ->expandableLimitedList()
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
                        // Derive status from user state
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
            ->recordActions([
                \Filament\Actions\Action::make('impersonate')
                    ->label('Login as')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Impersonate this Business Owner?')
                    ->modalDescription('You will be logged in as this user on their business dashboard. An option to exit impersonation will be available.')
                    ->visible(fn(User $record) => $record->tenants()->exists())
                    ->action(function (User $record) {
                        $service = new \App\Services\ImpersonationService();
                        $url = $service->generateImpersonationUrl($record);

                        if (!$url) {
                            \Filament\Notifications\Notification::make()
                                ->title('Impersonation failed')
                                ->body('This user does not have a valid tenant configuration.')
                                ->danger()
                                ->send();
                            return;
                        }

                        \Illuminate\Support\Facades\Log::info('Impersonation URL generated:', ['url' => $url, 'user_id' => $record->id]);

                        return redirect()->to($url);
                    }),
                \Filament\Actions\Action::make('assign_subscription')
                    ->label('Assign Subscription')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->visible(fn(User $record) => $record->tenants()->exists())
                    ->form([
                        Forms\Components\Select::make('tenant_id')
                            ->label('Business')
                            ->options(fn(User $record) => $record->tenants()->pluck('name', 'id'))
                            ->required()
                            ->default(fn(User $record) => $record->tenants()->first()?->id)
                            ->searchable(),
                        Forms\Components\Select::make('plan_id')
                            ->label('Plan')
                            ->options(\App\Models\PricingPlan::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Forms\Components\DateTimePicker::make('trial_ends_at')
                            ->label('Trial Ends At')
                            ->native(false),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Subscription Ends At')
                            ->native(false),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'trialing' => 'Trialing',
                                'canceled' => 'Canceled',
                                'past_due' => 'Past Due',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->action(function (User $record, array $data) {
                        $tenant = \App\Models\Tenant::find($data['tenant_id']);
                        $plan = \App\Models\PricingPlan::find($data['plan_id']);

                        if (!$tenant || !$plan) {
                            return;
                        }

                        // Update Tenant Plan Reference
                        $tenant->update([
                            'plan_id' => $plan->id,
                            'trial_ends_at' => $data['trial_ends_at'],
                        ]);

                        // Handle Manual Subscription Record (Mocking Cashier)
                        // This ensures 'Billable' checks pass if they check the database
                        $subscriptionData = [
                            'type' => 'default',
                            'stripe_id' => 'manually_assigned_' . \Illuminate\Support\Str::random(10),
                            'stripe_status' => $data['status'],
                            'stripe_price' => $plan->stripe_id ?? 'price_' . $plan->id,
                            'quantity' => 1,
                            'trial_ends_at' => $data['trial_ends_at'],
                            'ends_at' => $data['ends_at'],
                        ];

                        // Remove existing default subscriptions to avoid conflict
                        $tenant->subscriptions()->where('type', 'default')->delete();

                        // Create new subscription
                        $tenant->subscriptions()->create($subscriptionData);

                        // Log the action explicitly
                        try {
                            activity()
                                ->performedOn($tenant)
                                ->causedBy(auth()->user())
                                ->withProperties([
                                    'plan_id' => $plan->id,
                                    'plan_name' => $plan->name,
                                    'status' => $data['status'],
                                    'old_plan_id' => $tenant->getOriginal('plan_id'),
                                ])
                                ->log('assigned_subscription_manually');
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Failed to log subscription assignment activity: ' . $e->getMessage());
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Subscription Assigned')
                            ->body("Plan '{$plan->name}' assigned to '{$tenant->name}' as {$data['status']}.")
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Bulk actions can be added here
            ])
            ->emptyStateHeading('No business owners yet')
            ->emptyStateDescription('Business owners will appear here once they register and create tenants.')
            ->emptyStateIcon('heroicon-o-building-storefront');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
