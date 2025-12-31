<?php

namespace App\Filament\Dashboard\Pages;


use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;

class Calendar extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected string $view = 'filament.dashboard.pages.calendar';

    protected static string | UnitEnum | null $navigationGroup = 'Calendar';

    protected static ?int $navigationSort = -1;

    public static function canAccess(): bool
    {
        return has_feature('reservations');
    }

    protected static ?string $title = 'Calendar';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Dashboard\Widgets\CalendarWidget::class,
        ];
    }
}
