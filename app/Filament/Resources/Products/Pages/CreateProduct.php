<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Products\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
