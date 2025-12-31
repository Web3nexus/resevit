<?php

namespace App\Filament\Securegate\Resources;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\DocumentationArticleResource\Pages;
use App\Models\DocumentationArticle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DocumentationArticleResource extends Resource
{
    protected static ?string $model = DocumentationArticle::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | UnitEnum | null $navigationGroup = 'Landing Management';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Select::make('category')
                            ->options([
                                'getting-started' => 'Getting Started',
                                'features' => 'Features',
                                'faq' => 'FAQ',
                                'legal' => 'Legal',
                            ])
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Content')
                    ->schema([
                        TextInput::make('excerpt')
                            ->label('Short Description')
                            ->helperText('A brief summary displayed on search results or category pages.')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->extraInputAttributes(['style' => 'min-height: 400px']),
                    ]),

                Section::make('Settings')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->badge()
                    ->color('info'),

                ToggleColumn::make('is_published')
                    ->label('Published'),

                TextColumn::make('order')
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
            'index' => Pages\ListDocumentationArticles::route('/'),
            'create' => Pages\CreateDocumentationArticle::route('/create'),
            'edit' => Pages\EditDocumentationArticle::route('/{record}/edit'),
        ];
    }
}
