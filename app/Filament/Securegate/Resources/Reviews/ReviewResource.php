<?php

namespace App\Filament\Securegate\Resources\Reviews;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\Reviews\Pages\CreateReview;
use App\Filament\Securegate\Resources\Reviews\Pages\EditReview;
use App\Filament\Securegate\Resources\Reviews\Pages\ListReviews;
use App\Filament\Securegate\Resources\Reviews\Schemas\ReviewForm;
use App\Filament\Securegate\Resources\Reviews\Tables\ReviewsTable;
use App\Models\Review;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static string|UnitEnum|null $navigationGroup = 'Internal Users';

    public static function form(Schema $schema): Schema
    {
        return ReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewsTable::configure($table);
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
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
