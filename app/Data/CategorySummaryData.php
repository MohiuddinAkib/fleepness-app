<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Category;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class CategorySummaryData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
    ) {}

    public static function fromModel(Category $category): self
    {
        return new self(
            id: (int) $category->getKey(),
            name: $category->name,
            slug: $category->slug,
        );
    }
}
