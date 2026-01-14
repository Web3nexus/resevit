<?php

namespace App\Filament\Securegate\Resources\WithdrawalRequests\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WithdrawalRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('influencer_id')
                    ->relationship('influencer', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled(fn($record) => $record !== null) // Only disabled when editing
                    ->required(),
                TextInput::make('amount')
                    ->numeric()
                    ->prefix('$')
                    ->disabled(fn($record) => $record !== null) // Only disabled when editing
                    ->required()
                    ->helperText('Amount requested by the influencer'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'declined' => 'Declined',
                        'paid' => 'Paid',
                    ])
                    ->default('pending')
                    ->required()
                    ->live(),
                Textarea::make('admin_note')
                    ->label('Admin Note')
                    ->helperText('Internal notes about this withdrawal request')
                    ->columnSpanFull(),
                Section::make('Request Snapshot: Bank Details')
                    ->description('Bank details provided by the influencer at the time of this request.')
                    ->visible(fn($record) => $record !== null) // Only show when editing
                    ->schema([
                        Placeholder::make('bank_name')
                            ->content(fn($record) => $record?->bank_details['bank_name'] ?? 'N/A'),
                        Placeholder::make('account_name')
                            ->content(fn($record) => $record?->bank_details['account_name'] ?? 'N/A'),
                        Placeholder::make('account_number')
                            ->content(fn($record) => $record?->bank_details['account_number'] ?? 'N/A'),
                        Placeholder::make('iban')
                            ->label('IBAN')
                            ->content(fn($record) => $record?->bank_details['iban'] ?? 'N/A'),
                        Placeholder::make('swift_code')
                            ->label('SWIFT/BIC')
                            ->content(fn($record) => $record?->bank_details['swift_code'] ?? 'N/A'),
                    ])
                    ->columns(2)
                    ->compact(),
            ]);
    }
}
