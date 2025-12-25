<?php

namespace App\Filament\Securegate\Resources;

use App\Filament\Securegate\Resources\AiSettingResource\Pages;
use App\Models\AiSetting;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AiSettingResource extends Resource
{
    protected static ?string $model = AiSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|\UnitEnum|null $navigationGroup = 'AI Management';

    protected static ?string $navigationLabel = 'AI Configuration';

    public static function form(Schema $schema): Schema
    {
        return $schema

            ->schema([
                Forms\Components\Select::make('provider')
                    ->options([
                        'openai' => 'OpenAI',
                        'anthropic' => 'Anthropic (Claude)',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('api_key')
                    ->label('API Key')
                    ->password()
                    ->revealable()
                    ->required()
                    ->helperText('Your API key will be encrypted and never exposed in responses.'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Enable AI Features')
                    ->default(true),
                Forms\Components\TextInput::make('chat_model')
                    ->label('Chat / Reasoning Model')
                    ->default('gpt-4o-mini')
                    ->helperText('Used for: Marketing content, chat responses, intent detection')
                    ->required(),
                Forms\Components\TextInput::make('premium_model')
                    ->label('Premium Tasks Model')
                    ->default('gpt-4o')
                    ->helperText('Used for: Complex analysis, high-quality content generation')
                    ->required(),
                Forms\Components\TextInput::make('image_model')
                    ->label('Image Generation Model')
                    ->default('dall-e-3')
                    ->helperText('Used for: Marketing images, social media graphics'),
                Forms\Components\TextInput::make('embedding_model')
                    ->label('Embeddings Model')
                    ->default('text-embedding-3-large')
                    ->helperText('Used for: RAG, semantic search'),
                Forms\Components\TextInput::make('code_model')
                    ->label('Code Generation Model')
                    ->default('claude-3-5-sonnet-20241022')
                    ->helperText('Used for: Website builder, custom code generation'),
                Forms\Components\KeyValue::make('rate_limits')
                    ->label('Rate Limits')
                    ->keyLabel('Endpoint')
                    ->valueLabel('Requests per minute')
                    ->helperText('Optional: Set custom rate limits per API endpoint'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provider')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'openai' => 'success',
                        'anthropic' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('chat_model')
                    ->label('Chat Model'),
                Tables\Columns\TextColumn::make('premium_model')
                    ->label('Premium Model'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider')
                    ->options([
                        'openai' => 'OpenAI',
                        'anthropic' => 'Anthropic',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
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
            'index' => Pages\ListAiSettings::route('/'),
            'create' => Pages\CreateAiSetting::route('/create'),
            'edit' => Pages\EditAiSetting::route('/{record}/edit'),
        ];
    }
}
