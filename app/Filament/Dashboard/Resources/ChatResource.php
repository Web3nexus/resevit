<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\ChatResource\Pages;
use App\Models\Chat;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return has_feature('messaging');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Conversation Details')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->readOnly(),
                        Forms\Components\TextInput::make('source')
                            ->readOnly(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'closed' => 'Closed',
                                'archived' => 'Archived',
                            ])
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'whatsapp' => 'success',
                        'facebook' => 'info',
                        'instagram' => 'warning',
                        'google' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('unread_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_message_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'facebook' => 'Messenger',
                        'instagram' => 'Instagram',
                        'google' => 'Google Business',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                        'archived' => 'Archived',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('last_message_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ChatResource\RelationManagers\MessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChats::route('/'),
            'view' => Pages\ViewChat::route('/{record}'),
            'edit' => Pages\EditChat::route('/{record}/edit'),
        ];
    }
}
