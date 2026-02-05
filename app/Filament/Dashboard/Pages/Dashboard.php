<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Session;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleSetupGuide')
                ->label(fn() => Session::get('show_setup_guide', false) ? 'Hide Setup Guide' : 'Show Setup Guide')
                ->icon('heroicon-o-academic-cap')
                ->color('gray')
                ->action(function () {
                    Session::put('show_setup_guide', !Session::get('show_setup_guide', false));
                }),
        ];
    }
}
