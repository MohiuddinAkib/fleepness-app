<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Section;
use App\Enums\SectionType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class SectionData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $sectionName,
        public readonly ?string $title,
        public readonly ?string $sectionTitle,
        public readonly SectionType $type,
        public readonly string $sectionType,
        public readonly ?string $description,
        public readonly ?string $bio,
        public readonly ?string $placementType,
        public readonly int $sortOrder,
        public readonly int $index,
        public readonly ?int $categorySortOrder,
        public readonly ?int $catIndex,
        public readonly bool $isVisible,
        public readonly bool $visibility,
        public readonly ?string $backgroundImageUrl,
        public readonly ?string $backgroundImage,
        public readonly ?string $bannerImageUrl,
        public readonly ?string $bannerImage,
        public readonly bool $showProducts,
        public readonly ?TagData $tag,
        /**
         * Legacy compatibility aliases for clients still expecting a flattened tag shape.
         * Remove these after consumers migrate to the nested `tag` object.
         */
        public readonly ?int $tagId,
        public readonly ?string $tagName,
        public readonly ?CategoryData $category,
        /** @var list<array{id: int, title: ?string, description: ?string, image_url: ?string, image: ?string, bio: ?string, sort_order: int, index: int, is_visible: bool, visibility: bool, tag_id: ?int, tag_name: ?string}> */
        public readonly array $items,
    ) {}

    public static function fromModel(Section $section): self
    {
        $backgroundImageUrl = $section->getFirstMediaUrl('background_image') ?: null;
        $bannerImageUrl = $section->getFirstMediaUrl('banner_image') ?: null;
        $firstTag = $section->relationLoaded('items')
            ? $section->items->first(fn ($item) => $item->relationLoaded('tag') && null !== $item->tag)?->tag
            : null;

        return new self(
            id: (int) $section->getKey(),
            name: $section->name,
            sectionName: $section->name,
            title: $section->title,
            sectionTitle: $section->title,
            type: $section->type,
            sectionType: $section->type->value,
            description: $section->description,
            bio: $section->description,
            placementType: $section->placement_type,
            sortOrder: (int) $section->sort_order,
            index: (int) $section->sort_order,
            categorySortOrder: $section->category_sort_order,
            catIndex: $section->category_sort_order,
            isVisible: (bool) $section->is_visible,
            visibility: (bool) $section->is_visible,
            backgroundImageUrl: $backgroundImageUrl,
            backgroundImage: $backgroundImageUrl,
            bannerImageUrl: $bannerImageUrl,
            bannerImage: $bannerImageUrl,
            showProducts: $section->type->showsProducts(),
            tag: null !== $firstTag ? TagData::fromModel($firstTag) : null,
            tagId: $firstTag?->getKey(),
            tagName: $firstTag?->name,
            category: $section->relationLoaded('category') && null !== $section->category
                ? CategoryData::fromModel($section->category)
                : null,
            items: $section->relationLoaded('items')
                ? $section->items->map(fn ($item) => SectionItemData::fromModel($item)->toArray())->values()->all()
                : [],
        );
    }
}
