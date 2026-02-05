<?php

namespace App\Filament\Invest\Resources\InvestmentOpportunities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use App\Services\Finance\InvestmentService;
use App\Models\InvestmentOpportunity;
use App\Models\Investor;
use Filament\Notifications\Notification;

use Filament\Tables\Columns\ImageColumn;

class InvestmentOpportunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Preview')
                    ->circular()
                    ->stacked()
                    ->limit(3),
                TextColumn::make('title')
                    ->searchable()
                    ->description(fn($record) => $record->tenant?->name),
                BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'investment',
                        'warning' => 'crowdfunding',
                    ]),
                TextColumn::make('investment_round')
                    ->label('Round'),
                TextColumn::make('target_amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('raised_amount')
                    ->money('USD')
                    ->sortable(),
                BadgeColumn::make('reward_type')
                    ->colors([
                        'success' => 'roi',
                        'primary' => 'equity',
                        'warning' => 'perks',
                        'info' => 'other',
                    ]),
                BadgeColumn::make('status')
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'funded' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'funded' => 'Funded',
                    ])
                    ->default('active'),
            ])
            ->actions([ // Renamed from recordActions
                ViewAction::make(), // Added ViewAction
                Action::make('invest')
                    ->label('Invest Now')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        TextInput::make('amount')
                            ->label('Investment Amount')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->minValue(fn($record) => $record->min_investment)
                            ->helperText(fn($record) => "Minimum investment: $" . number_format($record->min_investment)),
                    ])
                    ->action(function (InvestmentOpportunity $record, array $data, InvestmentService $service) {
                        try {
                            /** @var Investor $investor */
                            $investor = auth()->user();
                            $service->invest($investor, $record, $data['amount']);

                            Notification::make()
                                ->title('Investment Successful')
                                ->body("You have successfully invested $" . number_format($data['amount']) . " in {$record->title}.")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Investment Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn($record) => $record->status === 'active'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
