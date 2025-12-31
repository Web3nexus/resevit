<?php

namespace App\Filament\Securegate\Resources\InvestmentOpportunities\Schemas;

use Filament\Schemas\Schema;

use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;

class InvestmentOpportunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Restaurant (Tenant)')
                    ->options(Tenant::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
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
                            ->required(),
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
                            ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        Select::make('reward_type')
                            ->options([
                                'roi' => 'ROI / Returns',
                                'equity' => 'Equity / Shares',
                                'perks' => 'Perks / Discounts',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Select::make('validation_status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),
                    ]),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'funded' => 'Funded',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                RichEditor::make('description')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->label('Promotional Images')
                    ->multiple()
                    ->image()
                    ->directory('opportunities/images')
                    ->reorderable()
                    ->getUploadedFileUrlUsing(fn($file) => \App\Helpers\StorageHelper::getUrl($file))
                    ->columnSpanFull(),
                FileUpload::make('videos')
                    ->label('Pitch / Promo Video')
                    ->multiple()
                    ->directory('opportunities/videos')
                    ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo'])
                    ->maxSize(50240)
                    ->getUploadedFileUrlUsing(fn($file) => \App\Helpers\StorageHelper::getUrl($file))
                    ->columnSpanFull(),
                DatePicker::make('expires_at'),
            ]);
    }
}
