<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Orders\OrderResource;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
