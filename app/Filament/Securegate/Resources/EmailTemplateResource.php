<?php

namespace App\Filament\Securegate\Resources;

use App\Models\EmailTemplate;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Securegate\Resources\EmailTemplateResource\Pages;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|\UnitEnum|null $navigationGroup = 'Marketing Tools';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Email Templates';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([

                Section::make('Template Information')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Template Key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique identifier for this template (e.g., order_confirmation)')
                            ->disabled(fn($record) => $record !== null), // Can't change key after creation

                        Forms\Components\TextInput::make('name')
                            ->label('Template Name')
                            ->required()
                            ->helperText('Human-readable name for this template'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active templates will be used for sending emails'),
                    ])
                    ->columns(1),

                Section::make('Email Content')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Email Subject')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('You can use variables like {{customer_name}}, {{order_number}}, etc.'),

                        Forms\Components\RichEditor::make('body_html')
                            ->label('HTML Body')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->helperText('HTML content of the email. Use {{variable_name}} for dynamic content.'),

                        Forms\Components\Textarea::make('body_text')
                            ->label('Plain Text Body (Optional)')
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('Plain text version for email clients that don\'t support HTML'),
                    ]),

                Section::make('Available Variables')
                    ->schema([
                        Forms\Components\Textarea::make('variables')
                            ->label('Available Variables')
                            ->rows(5)
                            ->columnSpanFull()
                            ->helperText('JSON array of available variables for this template (e.g., ["customer_name", "order_number", "total_amount"])')
                            ->placeholder('["customer_name", "order_number", "restaurant_name"]'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All templates')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
