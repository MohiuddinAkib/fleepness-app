<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\SizeTemplates\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Vendor\Resources\SizeTemplates\SizeTemplateResource;

class ListSizeTemplates extends ListRecords
{
    protected static string $resource = SizeTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
