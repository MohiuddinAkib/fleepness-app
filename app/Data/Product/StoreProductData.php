<?php

declare(strict_types=1);

namespace App\Data\Product;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class StoreProductData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly null|int|Optional $categoryId,
        public readonly null|int|Optional $sizeTemplateId,
        public readonly null|Optional|string $skuValue,
        public readonly null|float|Optional $sellingPrice,
        public readonly null|float|Optional $discountPrice,
        public readonly null|Optional|string $shortDescription,
        public readonly null|Optional|string $description,
        public readonly null|int|Optional $quantity,
    ) {}
}
