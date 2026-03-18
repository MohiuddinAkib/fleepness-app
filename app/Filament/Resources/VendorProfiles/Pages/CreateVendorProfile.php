<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorProfiles\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\VendorProfiles\VendorProfileResource;

class CreateVendorProfile extends CreateRecord
{
    protected static string $resource = VendorProfileResource::class;
}
