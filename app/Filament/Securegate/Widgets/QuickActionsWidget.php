<?php

namespace App\Filament\Securegate\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected string $view = 'filament.securegate.widgets.quick-actions-widget';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';
}
