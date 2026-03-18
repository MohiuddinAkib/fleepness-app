<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Category;
use Spatie\LaravelData\Data;
use App\Enums\CategoryStatus;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class CategoryData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly ?string $profileImageUrl,
        public readonly ?string $coverImageUrl,
        public readonly CategoryStatus $status,
        public readonly ?int $parentId,
    ) {}

    public static function fromModel(Category $category): self
    {
        return new self(
            id: (int) $category->getKey(),
            name: $category->name,
            slug: $category->slug,
            description: $category->description,
            profileImageUrl: $category->getFirstMediaUrl('profile_image') ?: null,
            coverImageUrl: $category->getFirstMediaUrl('cover_image') ?: null,
            status: $category->status,
            parentId: $category->parent_id,
        );
    }
}
