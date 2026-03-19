<?php

declare(strict_types=1);

namespace App\Filament\Resources\DeliveryOptions\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DeliveryOptions\DeliveryOptionResource;

class EditDeliveryOption extends EditRecord
{
    protected static string $resource = DeliveryOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
