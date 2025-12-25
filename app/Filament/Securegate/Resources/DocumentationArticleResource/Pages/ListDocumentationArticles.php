<?php

namespace App\Filament\Securegate\Resources\DocumentationArticleResource\Pages;

use App\Filament\Securegate\Resources\DocumentationArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentationArticles extends ListRecords
{
    protected static string $resource = DocumentationArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
