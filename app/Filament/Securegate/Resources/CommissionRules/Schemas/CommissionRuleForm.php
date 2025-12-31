<?php

namespace App\Filament\Securegate\Resources\CommissionRules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommissionRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pricing_plan_id')
                    ->numeric(),
                TextInput::make('commission_type')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('trigger_event')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
