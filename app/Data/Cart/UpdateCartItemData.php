<?php

declare(strict_types=1);

namespace App\Data\Cart;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;

#[MapInputName(SnakeCaseMapper::class)]
class UpdateCartItemData extends Data
{
    public function __construct(
        #[Min(1)]
        public readonly int|Optional $quantity,
        public readonly bool|Optional $isSelected,
    ) {}
}
