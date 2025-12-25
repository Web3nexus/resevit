<?php

namespace App\Filament\Invest\Resources\Investments\Pages;

use App\Filament\Invest\Resources\Investments\InvestmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvestments extends ListRecords
{
    protected static string $resource = InvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Invest\Widgets\InvestmentPerformanceChart::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Schemas\Components\Tabs\Tab::make('All Investments'),
            'active' => \Filament\Schemas\Components\Tabs\Tab::make('Active')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'pending')),
            'completed' => \Filament\Schemas\Components\Tabs\Tab::make('Completed')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'completed')),
            'cancelled' => \Filament\Schemas\Components\Tabs\Tab::make('Cancelled')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'cancelled')),
        ];
    }
}
