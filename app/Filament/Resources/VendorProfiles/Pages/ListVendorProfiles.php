<?php

namespace App\Filament\Resources\VendorProfiles\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VendorProfiles\VendorProfileResource;

class ListVendorProfiles extends ListRecords
{
    protected static string $resource = VendorProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
