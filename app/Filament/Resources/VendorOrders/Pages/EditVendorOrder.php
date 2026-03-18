<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorOrders\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VendorOrders\VendorOrderResource;

class EditVendorOrder extends EditRecord
{
    protected static string $resource = VendorOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
