<?php

namespace App\Filament\Securegate\Resources\ContactMessages;


use BackedEnum;
use UnitEnum;
use App\Filament\Securegate\Resources\ContactMessages\Pages\CreateContactMessage;
use App\Filament\Securegate\Resources\ContactMessages\Pages\EditContactMessage;
use App\Filament\Securegate\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Securegate\Resources\ContactMessages\Schemas\ContactMessageForm;
use App\Filament\Securegate\Resources\ContactMessages\Tables\ContactMessagesTable;
use App\Models\ContactMessage;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected static string | UnitEnum | null $navigationGroup = 'Landing Management';

    protected static ?int $navigationSort = 13;

    public static function form(Schema $schema): Schema
    {
        return ContactMessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactMessagesTable::configure($table);
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
            'index' => ListContactMessages::route('/'),
            'create' => CreateContactMessage::route('/create'),
            'edit' => EditContactMessage::route('/{record}/edit'),
        ];
    }
}
