<?php

namespace App\Filament\Securegate\Resources\WithdrawalRequests\Schemas;

use Filament\Schemas\Schema;

class WithdrawalRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('influencer_id')
                    ->relationship('influencer', 'name')
                    ->disabled()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->disabled()
                    ->prefix('$')
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'declined' => 'Declined',
                        'paid' => 'Paid',
                    ])
                    ->required()
                    ->live(),
                \Filament\Forms\Components\Textarea::make('admin_note')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Section::make('Request Snapshot: Bank Details')
                    ->description('Bank details provided by the influencer at the time of this request.')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('bank_name')
                            ->content(fn($record) => $record?->bank_details['bank_name'] ?? 'N/A'),
                        \Filament\Forms\Components\Placeholder::make('account_name')
                            ->content(fn($record) => $record?->bank_details['account_name'] ?? 'N/A'),
                        \Filament\Forms\Components\Placeholder::make('account_number')
                            ->content(fn($record) => $record?->bank_details['account_number'] ?? 'N/A'),
                        \Filament\Forms\Components\Placeholder::make('iban')
                            ->label('IBAN')
                            ->content(fn($record) => $record?->bank_details['iban'] ?? 'N/A'),
                        \Filament\Forms\Components\Placeholder::make('swift_code')
                            ->label('SWIFT/BIC')
                            ->content(fn($record) => $record?->bank_details['swift_code'] ?? 'N/A'),
                    ])
                    ->columns(2)
                    ->compact(),
            ]);
    }
}
