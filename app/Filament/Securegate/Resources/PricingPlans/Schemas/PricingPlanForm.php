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
                                TextInput::make('monthly_ai_credits')
                                    ->required()
                                    ->numeric()
                                    ->label('Monthly AI Credits')
                                    ->helperText('Credits awarded to the tenant each month.')
                                    ->default(0),
                            ]),
                    ])->columnSpanFull(),

                Section::make('Stripe Integration')
                    ->description('Configure Stripe product and price IDs for both test and live environments.')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        \Filament\Schemas\Components\Tabs::make('Stripe IDs')
                            ->tabs([
                                \Filament\Schemas\Components\Tabs\Tab::make('Test Environment')
                                    ->icon('heroicon-o-beaker')
                                    ->badge('Safe')
                                    ->badgeColor('success')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('stripe_product_id_test')
                                                    ->label('Product ID (Test)')
                                                    ->placeholder('prod_...')
                                                    ->helperText('Stripe product ID for test mode'),
                                                TextInput::make('stripe_price_id_test')
                                                    ->label('Monthly Price ID (Test)')
                                                    ->placeholder('price_...')
                                                    ->helperText('Monthly subscription price ID'),
                                                TextInput::make('stripe_yearly_price_id_test')
                                                    ->label('Yearly Price ID (Test)')
                                                    ->placeholder('price_...')
                                                    ->helperText('Yearly subscription price ID'),
                                            ]),
                                    ]),

                                \Filament\Schemas\Components\Tabs\Tab::make('Live Environment')
                                    ->icon('heroicon-o-bolt')
                                    ->badge('Production')
                                    ->badgeColor('danger')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('live_stripe_warning')
                                            ->content('⚠️ These IDs will be used for REAL payments when the platform is in live mode.')
                                            ->columnSpanFull(),

                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('stripe_product_id_live')
                                                    ->label('Product ID (Live)')
                                                    ->placeholder('prod_...')
                                                    ->helperText('Stripe product ID for live mode'),
                                                TextInput::make('stripe_price_id_live')
                                                    ->label('Monthly Price ID (Live)')
                                                    ->placeholder('price_...')
                                                    ->helperText('Monthly subscription price ID'),
                                                TextInput::make('stripe_yearly_price_id_live')
                                                    ->label('Yearly Price ID (Live)')
                                                    ->placeholder('price_...')
                                                    ->helperText('Yearly subscription price ID'),
                                            ]),
                                    ]),

                                \Filament\Schemas\Components\Tabs\Tab::make('Legacy')
                                    ->icon('heroicon-o-archive-box')
                                    ->badge('Deprecated')
                                    ->badgeColor('gray')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('legacy_notice')
                                            ->content('These fields are deprecated. Use the Test/Live environment tabs above.')
                                            ->columnSpanFull(),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('stripe_id')
                                                    ->label('Stripe Price ID (Monthly) - Legacy')
                                                    ->placeholder('price_H5v6pLQY6ax9js')
                                                    ->disabled()
                                                    ->dehydrated(false),
                                                TextInput::make('stripe_yearly_id')
                                                    ->label('Stripe Price ID (Yearly) - Legacy')
                                                    ->placeholder('price_H5v6pLQY6ax9js_yearly')
                                                    ->disabled()
                                                    ->dehydrated(false),
                                            ]),
                                    ]),
                            ])
                            ->columnSpanFull(),
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
