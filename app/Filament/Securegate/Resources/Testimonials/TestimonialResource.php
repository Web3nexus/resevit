<?php

namespace App\Filament\Securegate\Resources\Testimonials;

use App\Filament\Securegate\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Securegate\Resources\Testimonials\Pages\EditTestimonial;
use App\Filament\Securegate\Resources\Testimonials\Pages\ListTestimonials;
use App\Filament\Securegate\Resources\Testimonials\Schemas\TestimonialForm;
use App\Filament\Securegate\Resources\Testimonials\Tables\TestimonialsTable;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Landing Management';

    protected static ?int $navigationSort = 12;

    public static function form(Schema $schema): Schema
    {
        return TestimonialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestimonialsTable::configure($table);
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
            'index' => ListTestimonials::route('/'),
            'create' => CreateTestimonial::route('/create'),
            'edit' => EditTestimonial::route('/{record}/edit'),
        ];
    }
}
