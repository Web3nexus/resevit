<?php

namespace App\Filament\Dashboard\Resources;


use BackedEnum;
use UnitEnum;
use App\Filament\Dashboard\Resources\MarketingCampaignResource\Pages;
use App\Models\MarketingCampaign;
use App\Services\AI\ContentGeneratorService;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Actions\Action;

class MarketingCampaignResource extends Resource
{
    protected static ?string $model = MarketingCampaign::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    public static function canViewAny(): bool
    {
        return has_feature('marketing');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'social' => 'Social Media',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('Schedule For')
                    ->native(false),
                Forms\Components\TextInput::make('subject')
                    ->visible(fn(\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->required(fn(\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->maxLength(255),
                Forms\Components\Textarea::make('ai_prompt')
                    ->label('AI Prompt (for future AI generation)')
                    ->helperText('Describe what you want the content to be about.')
                    ->rows(2),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Campaign Image')
                    ->image()
                    ->directory('marketing-images')
                    ->visibility('public')
                    ->visible(fn(\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'social')
                    ->getUploadedFileUrlUsing(fn($record) => \App\Helpers\StorageHelper::getUrl($record->image_path))
                    ->helperText('Upload an image for your social media post'),
                Forms\Components\RichEditor::make('content')
                    ->visible(fn(\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->required(fn(\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'email')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->visible(fn(\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'email')
                    ->required(fn(\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'email')
                    ->rows(5)
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'sending' => 'info',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'social' => 'Social Media',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'sent' => 'Sent',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('send')
                    ->label('Send Now')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->action(function (MarketingCampaign $record, \App\Services\Marketing\CampaignSenderService $sender) {
                        $sender->send($record);
                    })
                    ->visible(fn(MarketingCampaign $record) => $record->status === 'draft' || $record->status === 'failed'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
