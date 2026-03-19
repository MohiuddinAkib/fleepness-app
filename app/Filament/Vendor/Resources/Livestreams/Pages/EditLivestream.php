<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\Livestreams\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Vendor\Resources\Livestreams\LivestreamResource;

class EditLivestream extends EditRecord
{
    protected static string $resource = LivestreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
