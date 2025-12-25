<?php

namespace App\Filament\Securegate\Resources\Tickets\Pages;

use App\Filament\Securegate\Resources\Tickets\TicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTickets extends ManageRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->using(function (array $data, string $model): \App\Models\Ticket {
                    $description = $data['description'];
                    unset($data['description']);

                    $data['ticketable_id'] = \Filament\Facades\Filament::auth()->id();
                    $data['ticketable_type'] = get_class(\Filament\Facades\Filament::auth()->user());
                    // Admin tickets might not belong to a specific tenant unless we add a field for it.
                    // For now, leaving tenant_id null or implied.
        
                    $ticket = $model::create($data);

                    $ticket->messages()->create([
                        'user_id' => \Filament\Facades\Filament::auth()->id(),
                        'user_type' => get_class(\Filament\Facades\Filament::auth()->user()),
                        'message' => $description,
                    ]);

                    return $ticket;
                }),
        ];
    }
}
