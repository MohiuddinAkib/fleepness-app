<?php

declare(strict_types=1);

namespace App\Filament\Resources\ShopCategories\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ShopCategories\ShopCategoryResource;

class CreateShopCategory extends CreateRecord
{
    protected static string $resource = ShopCategoryResource::class;
}
