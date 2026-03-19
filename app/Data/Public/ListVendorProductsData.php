<?php

declare(strict_types=1);

namespace App\Data\Public;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListVendorProductsData extends Data
{
    public function __construct(
        public readonly ?string $q = null,
        public readonly ?float $minPrice = null,
        public readonly ?float $maxPrice = null,
        public readonly ?string $priceCategory = null,
    ) {}
}
