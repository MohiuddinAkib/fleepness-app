<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\ShopCategory;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class ShopCategoryData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
    ) {}

    public static function fromModel(ShopCategory $shopCategory): self
    {
        return new self(
            id: (int) $shopCategory->getKey(),
            name: $shopCategory->name,
            slug: $shopCategory->slug,
            description: $shopCategory->description,
        );
    }
}
