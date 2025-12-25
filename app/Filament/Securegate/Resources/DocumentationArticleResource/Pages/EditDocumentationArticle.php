<?php

namespace App\Filament\Securegate\Resources\DocumentationArticleResource\Pages;

use App\Filament\Securegate\Resources\DocumentationArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentationArticle extends EditRecord
{
    protected static string $resource = DocumentationArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
