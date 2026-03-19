<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListOwnProductsData extends Data
{
    public function __construct(
        public readonly ?string $q = null,
    ) {}
}
