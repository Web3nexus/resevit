<?php

namespace App\Filament\Securegate\Resources\Influencers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InfluencerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                TextInput::make('referral_code')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                Textarea::make('bio')
                    ->rows(5)
                    ->columnSpanFull(),
                TextInput::make('website')
                    ->url(),
                \Filament\Forms\Components\Repeater::make('social_links')
                    ->schema([
                        TextInput::make('platform')->required()->placeholder('e.g. Instagram'),
                        TextInput::make('url')->url()->required()->placeholder('https://...'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                TextInput::make('stripe_account_id')
                    ->label('Stripe Connect ID')
                    ->placeholder('acct_...'),
                \Filament\Forms\Components\Section::make('Banking Details')
                    ->description('Bank account information for affiliate payouts.')
                    ->schema([
                        TextInput::make('bank_name'),
                        TextInput::make('account_name'),
                        TextInput::make('account_number'),
                        TextInput::make('iban')->label('IBAN'),
                        TextInput::make('swift_code')->label('SWIFT/BIC Code'),
                    ])
                    ->columns(2),
            ]);
    }
}
