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
                TextInput::make('referral_code')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('website')
                    ->url(),
                TextInput::make('social_links'),
                TextInput::make('stripe_account_id'),
            ]);
    }
}
