<?php

declare(strict_types=1);

namespace App\Data\Public;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListVendorsData extends Data
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?int $shopCategoryId = null,
        public readonly ?int $similarToVendorId = null,
    ) {}
}
