<?php

namespace App\Filament\Securegate\Resources\PricingPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PricingPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Basics')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('stripe_id')
                                    ->label('Stripe Price ID (Monthly)')
                                    ->placeholder('price_H5v6pLQY6ax9js'),
                                TextInput::make('stripe_yearly_id')
                                    ->label('Stripe Price ID (Yearly)')
                                    ->placeholder('price_H5v6pLQY6ax9js_yearly'),
                            ]),
                        TextInput::make('description'),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('price_monthly')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$'),
                                TextInput::make('price_yearly')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$'),
                            ]),
                    ])->columnSpanFull(),

                Section::make('Call to Action')
                    ->schema([
                        TextInput::make('cta_text')
                            ->required()
                            ->default('Get Started'),
                        TextInput::make('cta_url'),
                    ])->columns(2)->columnSpanFull(),

                Section::make('Features')
                    ->schema([
                        Repeater::make('planFeatures')
                            ->relationship('planFeatures')
                            ->schema([
                                Select::make('pricing_feature_id')
                                    ->label('Feature')
                                    ->options(\App\Models\PricingFeature::pluck('name', 'id'))
                                    ->required()
                                    ->searchable(),
                                Toggle::make('is_included')
                                    ->default(true),
                                TextInput::make('value')
                                    ->placeholder('e.g. 5 Users'),
                                TextInput::make('limit_value')
                                    ->label('Numeric Limit')
                                    ->numeric()
                                    ->placeholder('e.g. 5 for code limit'),
                            ])
                            ->columns(4) // Increased columns for full width
                            ->itemLabel(fn(array $state): ?string => \App\Models\PricingFeature::find($state['pricing_feature_id'] ?? null)?->name ?? null),
                    ])->columnSpanFull(),

                Section::make('Settings')
                    ->schema([
                        Toggle::make('is_featured')
                            ->required()
                            ->default(false),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true),
                        Toggle::make('is_free')
                            ->label('Is Free Plan')
                            ->default(false),
                        TextInput::make('trial_days')
                            ->numeric()
                            ->default(7),
                        Toggle::make('is_trial_available')
                            ->label('Trial Available')
                            ->default(true),
                        TextInput::make('order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])->columns(3)->columnSpanFull(),
            ]);
    }
}
