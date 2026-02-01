<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\NotificationTemplateResource\Pages;
// Needs to be created or mapped to existing
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    // Map to 'email_templates' table for now, or create a unified model
    protected static ?string $model = \App\Models\EmailTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Message Templates';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subject')
                    ->visible(fn ($get) => $get('type') === 'email')
                    ->required(fn ($get) => $get('type') === 'email')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('content')
                    ->label(fn ($get) => $get('type') === 'email' ? 'Email Body' : 'SMS Content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('plain_content')
                    ->label('Plain Text Version (Email Only)')
                    ->visible(fn ($get) => $get('type') === 'email')
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('variables')
                    ->label('Available Variables')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'email' => 'info',
                        'sms' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
