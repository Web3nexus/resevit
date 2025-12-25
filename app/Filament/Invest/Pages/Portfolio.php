<?php

namespace App\Filament\Invest\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Models\Investor;

use BackedEnum;

class Portfolio extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected string $view = 'filament.invest.pages.portfolio';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
