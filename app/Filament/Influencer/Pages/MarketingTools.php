<?php

namespace App\Filament\Influencer\Pages;

use App\Models\MarketingMaterial;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class MarketingTools extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected string $view = 'filament.influencer.pages.marketing-tools';

    protected static ?string $title = 'Marketing Tools';

    public function getViewData(): array
    {
        return [
            'materials' => MarketingMaterial::where('is_active', true)->latest()->get(),
            'influencer' => auth('influencer')->user(),
        ];
    }
}
