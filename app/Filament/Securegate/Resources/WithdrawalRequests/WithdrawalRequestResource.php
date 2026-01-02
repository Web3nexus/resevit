<?php

namespace App\Filament\Securegate\Resources\WithdrawalRequests;

use App\Filament\Securegate\Resources\WithdrawalRequests\Pages\CreateWithdrawalRequest;
use App\Filament\Securegate\Resources\WithdrawalRequests\Pages\EditWithdrawalRequest;
use App\Filament\Securegate\Resources\WithdrawalRequests\Pages\ListWithdrawalRequests;
use App\Filament\Securegate\Resources\WithdrawalRequests\Pages\ViewWithdrawalRequest;
use App\Filament\Securegate\Resources\WithdrawalRequests\Schemas\WithdrawalRequestForm;
use App\Filament\Securegate\Resources\WithdrawalRequests\Schemas\WithdrawalRequestInfolist;
use App\Filament\Securegate\Resources\WithdrawalRequests\Tables\WithdrawalRequestsTable;
use App\Models\WithdrawalRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WithdrawalRequestResource extends Resource
{
    protected static ?string $model = WithdrawalRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WithdrawalRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WithdrawalRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WithdrawalRequestsTable::configure($table);
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
            'index' => ListWithdrawalRequests::route('/'),
            'create' => CreateWithdrawalRequest::route('/create'),
            'view' => ViewWithdrawalRequest::route('/{record}'),
            'edit' => EditWithdrawalRequest::route('/{record}/edit'),
        ];
    }
}
