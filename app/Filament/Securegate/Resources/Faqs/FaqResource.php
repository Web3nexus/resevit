<?php

namespace App\Filament\Securegate\Resources\Faqs;

use App\Filament\Securegate\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Securegate\Resources\Faqs\Pages\EditFaq;
use App\Filament\Securegate\Resources\Faqs\Pages\ListFaqs;
use App\Filament\Securegate\Resources\Faqs\Schemas\FaqForm;
use App\Filament\Securegate\Resources\Faqs\Tables\FaqsTable;
use App\Models\Faq;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'Landing Management';

    protected static ?int $navigationSort = 16;

    public static function form(Schema $schema): Schema
    {
        return FaqForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqsTable::configure($table);
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
            'index' => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'edit' => EditFaq::route('/{record}/edit'),
        ];
    }
}
