<?php

namespace App\Filament\Securegate\Resources\InvestmentOpportunities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\InvestmentOpportunity;
use Filament\Tables\Filters\SelectFilter;

class InvestmentOpportunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('tenant.name')
                    ->label('Restaurant')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'investment' => 'primary',
                        'crowdfunding' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('target_amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('validation_status')
                    ->label('Review Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'funded' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('validation_status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'investment' => 'Investment',
                        'crowdfunding' => 'Crowdfunding',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(InvestmentOpportunity $record) => $record->validation_status === 'pending')
                    ->action(function (InvestmentOpportunity $record) {
                        $record->update(['validation_status' => 'approved']);
                        Notification::make()->title('Opportunity Approved')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(InvestmentOpportunity $record) => $record->validation_status === 'pending')
                    ->action(function (InvestmentOpportunity $record) {
                        $record->update(['validation_status' => 'rejected']);
                        Notification::make()->title('Opportunity Rejected')->danger()->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
