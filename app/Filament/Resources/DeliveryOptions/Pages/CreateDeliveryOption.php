<?php

declare(strict_types=1);

namespace App\Filament\Resources\DeliveryOptions\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DeliveryOptions\DeliveryOptionResource;

class CreateDeliveryOption extends CreateRecord
{
    protected static string $resource = DeliveryOptionResource::class;
}
