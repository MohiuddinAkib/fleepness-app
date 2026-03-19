<?php

declare(strict_types=1);

namespace App\Filament\Resources\DeliveryOptions\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DeliveryOptions\DeliveryOptionResource;

class ListDeliveryOptions extends ListRecords
{
    protected static string $resource = DeliveryOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
