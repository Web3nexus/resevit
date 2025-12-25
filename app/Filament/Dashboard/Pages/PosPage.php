<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;

class PosPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-computer-desktop';

    protected string $view = 'filament.dashboard.pages.pos-page';

    protected static ?string $title = 'Point of Sale';

    protected static ?string $slug = 'pos';

    protected static string|\UnitEnum|null $navigationGroup = 'Menu Management';

    protected static ?int $navigationSort = 10;

    // We can add mount logic or authentication checks here if needed.
}
