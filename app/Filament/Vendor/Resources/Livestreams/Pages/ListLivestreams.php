<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\Livestreams\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Vendor\Resources\Livestreams\LivestreamResource;

class ListLivestreams extends ListRecords
{
    protected static string $resource = LivestreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
