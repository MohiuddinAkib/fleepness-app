<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Category;
use Spatie\LaravelData\Data;
use App\Enums\CategoryStatus;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\DataCollectionOf;

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
        /** @var array{id: int, name: string, slug: string}|null */
        public readonly ?array $parent,
        #[DataCollectionOf(CategorySummaryData::class)]
        public readonly DataCollection $children,
        /**
         * Legacy compatibility alias for clients still expecting a flattened parent reference.
         * Remove this after consumers migrate to the nested `parent` object.
         */
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
            parent: $category->relationLoaded('parent') && null !== $category->parent
                ? CategorySummaryData::fromModel($category->parent)->toArray()
                : null,
            children: $category->relationLoaded('children')
                ? new DataCollection(CategorySummaryData::class, $category->children->map(
                    fn (Category $child) => CategorySummaryData::fromModel($child)
                ))
                : new DataCollection(CategorySummaryData::class, []),
            parentId: $category->parent_id,
        );
    }
}
