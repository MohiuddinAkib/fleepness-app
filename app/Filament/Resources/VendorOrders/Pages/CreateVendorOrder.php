<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorOrders\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\VendorOrders\VendorOrderResource;

class CreateVendorOrder extends CreateRecord
{
    protected static string $resource = VendorOrderResource::class;
}
