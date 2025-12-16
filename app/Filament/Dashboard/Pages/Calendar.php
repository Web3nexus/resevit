<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;

class Calendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected string $view = 'filament.dashboard.pages.calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'Calendar';

    protected static ?int $navigationSort = -1;

    protected static ?string $title = 'Calendar';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Dashboard\Components\CalendarWidget::class,
        ];
    }
}
