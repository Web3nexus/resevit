<?php

namespace App\Filament\Dashboard\Resources\StaffSchedules;


use BackedEnum;
use UnitEnum;
use App\Filament\Dashboard\Resources\StaffSchedules\Pages\CreateStaffSchedule;
use App\Filament\Dashboard\Resources\StaffSchedules\Pages\EditStaffSchedule;
use App\Filament\Dashboard\Resources\StaffSchedules\Pages\ListStaffSchedules;
use App\Filament\Dashboard\Resources\StaffSchedules\Schemas\StaffScheduleForm;
use App\Filament\Dashboard\Resources\StaffSchedules\Tables\StaffSchedulesTable;
use App\Models\StaffSchedule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StaffScheduleResource extends Resource
{
    protected static ?string $model = StaffSchedule::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string | UnitEnum | null $navigationGroup = 'Staff Management';

    public static function canViewAny(): bool
    {
        return has_feature('staff_scheduling');
    }

    public static function form(Schema $schema): Schema
    {
        return StaffScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffSchedulesTable::configure($table);
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
            'index' => ListStaffSchedules::route('/'),
            'create' => CreateStaffSchedule::route('/create'),
            'edit' => EditStaffSchedule::route('/{record}/edit'),
        ];
    }
}
