<?php

namespace App\Filament\Securegate\Resources\CommissionRules\Pages;

use App\Filament\Securegate\Resources\CommissionRules\CommissionRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCommissionRule extends EditRecord
{
    protected static string $resource = CommissionRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
