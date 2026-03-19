<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\VendorOrders\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Vendor\Resources\VendorOrders\VendorOrderResource;

class ListVendorOrders extends ListRecords
{
    protected static string $resource = VendorOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
