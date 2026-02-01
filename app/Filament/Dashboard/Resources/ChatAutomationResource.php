<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\ChatAutomationResource\Pages;
use App\Models\AutomationFlow;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ChatAutomationResource extends Resource
{
    protected static ?string $model = AutomationFlow::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return has_feature('messaging');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Flow Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('trigger_type')
                            ->options([
                                'keyword' => 'Keyword Match',
                                'welcome' => 'Welcome Message',
                                'button' => 'Button Click',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),

                \Filament\Schemas\Components\Section::make('Triggers')
                    ->schema([
                        Forms\Components\Repeater::make('triggers')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('trigger_key')
                                    ->default('keyword')
                                    ->required(),
                                Forms\Components\TextInput::make('trigger_value')
                                    ->placeholder('e.g. book, reserve')
                                    ->required(),
                            ])
                            ->columns(2),
                    ]),

                \Filament\Schemas\Components\Section::make('Automation Steps')
                    ->schema([
                        Forms\Components\Repeater::make('steps')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'message' => 'Send Message',
                                        'action' => 'Perform Action',
                                    ])
                                    ->required()
                                    ->live(),

                                Forms\Components\Textarea::make('content')
                                    ->label('Message Content')
                                    ->visible(fn ($get) => $get('type') === 'message')
                                    ->required(),

                                Forms\Components\Select::make('action')
                                    ->options([
                                        'create_reservation' => 'Create Reservation',
                                        'tag_chat' => 'Tag Conversation',
                                    ])
                                    ->visible(fn ($get) => $get('type') === 'action')
                                    ->required(),

                                Forms\Components\Toggle::make('auto_progress')
                                    ->label('Auto-progress to next step')
                                    ->default(false),
                            ])
                            ->reorderableWithButtons(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trigger_type')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
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
            ChatAutomationResource\RelationManagers\AutomationLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChatAutomations::route('/'),
            'create' => Pages\CreateChatAutomation::route('/create'),
            'edit' => Pages\EditChatAutomation::route('/{record}/edit'),
        ];
    }
}
