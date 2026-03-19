<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\ShortVideos\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Vendor\Resources\ShortVideos\ShortVideoResource;

class ListShortVideos extends ListRecords
{
    protected static string $resource = ShortVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
