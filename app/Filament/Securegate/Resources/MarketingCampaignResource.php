<?php

namespace App\Filament\Securegate\Resources;

use App\Filament\Securegate\Resources\MarketingCampaignResource\Pages;
use App\Models\MarketingCampaign;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MarketingCampaignResource extends Resource
{
    protected static ?string $model = MarketingCampaign::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';

    protected static \UnitEnum|string|null $navigationGroup = 'Marketing Tools';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Campaign Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull()
                            ->label('Campaign Name'),

                        TextInput::make('subject')
                            ->required()
                            ->columnSpanFull()
                            ->label('Email Subject'),
                    ]),

                Section::make('Content')
                    ->schema([
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->extraInputAttributes(['style' => 'min-height: 400px'])
                            ->label('Email Content'),
                    ]),

                Section::make('Recipients & Scheduling')
                    ->schema([
                        Select::make('recipient_type')
                            ->options([
                                'all_users' => 'All Users',
                                'investors' => 'Investors Only',
                                'customers' => 'Customers Only',
                                'newsletter_subscribers' => 'Newsletter Subscribers',
                            ])
                            ->required()
                            ->default('all_users')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'scheduled' => 'Scheduled',
                                'sent' => 'Sent',
                            ])
                            ->required()
                            ->default('draft')
                            ->columnSpanFull(),

                        DateTimePicker::make('scheduled_at')
                            ->label('Schedule Send Time')
                            ->columnSpanFull()
                            ->visible(fn($get) => $get('status') === 'scheduled'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('recipient_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'sending' => 'info',
                        'sent' => 'success',
                        'failed' => 'danger',
                    }),

                TextColumn::make('sent_count')
                    ->label('Sent')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingCampaigns::route('/'),
            'create' => Pages\CreateMarketingCampaign::route('/create'),
            'edit' => Pages\EditMarketingCampaign::route('/{record}/edit'),
        ];
    }
}
