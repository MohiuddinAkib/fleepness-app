<?php

declare(strict_types=1);

namespace App\Data\Livestream;

use App\Models\Product;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;

#[MapName(SnakeCaseMapper::class)]
class AttachProductData extends Data
{
    public function __construct(
        #[ Exists(Product::class, 'id'), IntegerType]
        public int $productId,
    ) {}
}
