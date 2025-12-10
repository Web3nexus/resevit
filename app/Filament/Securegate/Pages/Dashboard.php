<?php

namespace App\Filament\Securegate\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }
}
