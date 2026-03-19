<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\ShortVideos\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Vendor\Resources\ShortVideos\ShortVideoResource;

class EditShortVideo extends EditRecord
{
    protected static string $resource = ShortVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
