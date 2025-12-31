<?php

namespace App\Filament\Securegate\Resources\NewsletterSubscribers;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\NewsletterSubscribers\Pages\CreateNewsletterSubscriber;
use App\Filament\Securegate\Resources\NewsletterSubscribers\Pages\EditNewsletterSubscriber;
use App\Filament\Securegate\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use App\Filament\Securegate\Resources\NewsletterSubscribers\Schemas\NewsletterSubscriberForm;
use App\Filament\Securegate\Resources\NewsletterSubscribers\Tables\NewsletterSubscribersTable;
use App\Models\NewsletterSubscriber;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-at-symbol';

    protected static string | UnitEnum | null $navigationGroup = 'Marketing Tools';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return NewsletterSubscriberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsletterSubscribersTable::configure($table);
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
            'index' => ListNewsletterSubscribers::route('/'),
            'create' => CreateNewsletterSubscriber::route('/create'),
            'edit' => EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
}
