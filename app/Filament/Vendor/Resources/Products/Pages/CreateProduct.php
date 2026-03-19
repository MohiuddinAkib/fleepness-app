<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\Products\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Vendor\Resources\Products\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
