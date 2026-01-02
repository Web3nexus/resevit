<?php

namespace App\Filament\Securegate\Resources\CommissionRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommissionRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pricing_plan_id')
                    ->relationship('plan', 'name')
                    ->placeholder('Global (All Plans)')
                    ->label('Pricing Plan'),
                Select::make('commission_type')
                    ->options([
                        'percentage' => 'Percentage (%)',
                        'fixed' => 'Fixed Amount',
                    ])
                    ->required()
                    ->live(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->label(fn(\Filament\Forms\Get $get) => $get('commission_type') === 'percentage' ? 'Percentage' : 'Amount'),
                TextInput::make('currency')
                    ->required()
                    ->default('USD')
                    ->visible(fn(\Filament\Forms\Get $get) => $get('commission_type') === 'fixed'),
                Select::make('trigger_event')
                    ->options([
                        'signup' => 'New Signup',
                        'subscription_payment' => 'Subscription Payment',
                    ])
                    ->required(),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
