<?php

namespace App\Filament\Securegate\Resources\LandingPages\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'Sections';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'announcement_bar' => 'Announcement Bar',
                        'hero' => 'Hero',
                        'logo_cloud' => 'Logo Cloud',
                        'features' => 'Key Features (3 Cards)',
                        'connected_tools' => 'Connected Tools',
                        'workflow' => 'How It Works',
                        'stats' => 'Stats Band',
                        'resources_preview' => 'Resources Preview',
                        'testimonials' => 'Testimonials',
                        'cta_banner' => 'Final CTA Banner',
                    ])
                    ->required()
                    ->live(),
                \Filament\Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('subtitle')
                    ->maxLength(255),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true),

                \Filament\Schemas\Components\Section::make('Section Items')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->orderColumn('order')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title'),
                                \Filament\Forms\Components\TextInput::make('subtitle'),
                                \Filament\Forms\Components\Textarea::make('description'),
                                \Filament\Forms\Components\TextInput::make('icon')
                                    ->helperText('Heroicon name, SVG path, or External Image URL'),
                                \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->collection('images')
                                    ->disk('public')
                                    ->image(),
                                \Filament\Forms\Components\TextInput::make('link_url'),
                                \Filament\Forms\Components\TextInput::make('link_text'),
                            ])
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                    ])
                    ->description('Add individual items (cards, logos, stats) to this section.'),

                \Filament\Schemas\Components\Section::make('Custom Configuration')
                    ->schema([
                        \Filament\Forms\Components\KeyValue::make('content')
                            ->helperText('Extra configuration for this specific section type.'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color('info'),
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                \Filament\Tables\Columns\ToggleColumn::make('is_active'),
                \Filament\Tables\Columns\TextColumn::make('order')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'hero' => 'Hero',
                        'features' => 'Features',
                        'stats' => 'Stats',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
