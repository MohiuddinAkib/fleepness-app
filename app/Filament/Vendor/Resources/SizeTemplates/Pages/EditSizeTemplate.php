<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\SizeTemplates\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Vendor\Resources\SizeTemplates\SizeTemplateResource;

class EditSizeTemplate extends EditRecord
{
    protected static string $resource = SizeTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
