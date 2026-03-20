<?php

declare(strict_types=1);

namespace App\Data\Response\Product;

use App\Enums\ProductStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class ProductStatusData extends Data
{
    public function __construct(
        public readonly ProductStatus $status,
    ) {}
}
