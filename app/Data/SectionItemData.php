<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\SectionItem;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class SectionItemData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $imageUrl,
        public readonly ?string $image,
        public readonly ?string $bio,
        public readonly int $sortOrder,
        public readonly int $index,
        public readonly bool $isVisible,
        public readonly bool $visibility,
        public readonly ?TagData $tag,
    ) {}

    public static function fromModel(SectionItem $sectionItem): self
    {
        $tag = $sectionItem->relationLoaded('tag') ? $sectionItem->tag : null;
        $imageUrl = $sectionItem->getFirstMediaUrl('image') ?: null;

        return new self(
            id: (int) $sectionItem->getKey(),
            title: $sectionItem->title,
            description: $sectionItem->description,
            imageUrl: $imageUrl,
            image: $imageUrl,
            bio: $sectionItem->description,
            sortOrder: (int) $sectionItem->sort_order,
            index: (int) $sectionItem->sort_order,
            isVisible: (bool) $sectionItem->is_visible,
            visibility: (bool) $sectionItem->is_visible,
            tag: null !== $tag ? TagData::fromModel($tag) : null,
        );
    }
}
