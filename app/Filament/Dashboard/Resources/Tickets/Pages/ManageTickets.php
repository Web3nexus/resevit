<?php

namespace App\Filament\Dashboard\Resources\Tickets\Pages;

use App\Filament\Dashboard\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageTickets extends ManageRecords
{
    protected static string $resource = TicketResource::class;

    public function getTabs(): array
    {
        return [
            'inbox' => Tab::make('Client Support')
                ->icon('heroicon-o-inbox')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('tenant_id', tenant('id'))
                        ->where('ticketable_type', '!=', get_class(auth()->user()))
                ),
            'sent' => Tab::make('My Support Requests')
                ->icon('heroicon-o-paper-airplane')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('ticketable_id', auth()->id())
                        ->where('ticketable_type', get_class(auth()->user()))
                ),
        ];
    }
}
