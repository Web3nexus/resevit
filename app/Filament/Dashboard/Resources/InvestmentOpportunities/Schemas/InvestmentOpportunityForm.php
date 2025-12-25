<?php

namespace App\Filament\Dashboard\Resources\InvestmentOpportunities\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;

class InvestmentOpportunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tenant_id')
                    ->default(fn() => tenancy()->tenant->id),
                Hidden::make('validation_status')
                    ->default('pending'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Grid::make(2)
                    ->schema([
                        Select::make('type')
                            ->options([
                                'investment' => 'Traditional Investment',
                                'crowdfunding' => 'Crowdfunding',
                            ])
                            ->required()
                            ->default('investment'),
                        TextInput::make('investment_round')
                            ->placeholder('e.g. Seed, Series A, Community Round'),
                    ]),
                Grid::make(3)
                    ->schema([
                        TextInput::make('target_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        TextInput::make('min_investment')
                            ->numeric()
                            ->prefix('$')
                            ->default(100)
                            ->required(),
                        TextInput::make('roi_percentage')
                            ->label('Expected ROI (%)')
                            ->numeric()
                            ->suffix('%')
                            ->required()
                            ->visible(fn(callable $get) => $get('reward_type') === 'roi' || !$get('reward_type')),
                        TextInput::make('equity_percentage')
                            ->label('Equity Offered (%)')
                            ->numeric()
                            ->suffix('%')
                            ->required()
                            ->visible(fn(callable $get) => $get('reward_type') === 'equity'),
                        TextInput::make('reward_details')
                            ->label('Reward Details')
                            ->placeholder('e.g. 10% discount for life')
                            ->required()
                            ->visible(fn(callable $get) => in_array($get('reward_type'), ['perks', 'other'])),
                    ]),
                Select::make('reward_type')
                    ->options([
                        'roi' => 'ROI / Returns',
                        'equity' => 'Equity / Shares',
                        'perks' => 'Perks / Discounts',
                        'other' => 'Other',
                    ])
                    ->required()
                    ->default('roi')
                    ->live(),
                RichEditor::make('description')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->label('Promotional Images')
                    ->multiple()
                    ->image()
                    ->directory('opportunities/images')
                    ->reorderable()
                    ->columnSpanFull(),
                FileUpload::make('videos')
                    ->label('Pitch / Promo Video')
                    ->multiple()
                    ->directory('opportunities/videos')
                    ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo'])
                    ->maxSize(50240) // 50MB
                    ->columnSpanFull(),
                DatePicker::make('expires_at'),
            ]);
    }
}
