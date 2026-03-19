<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Slider;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class SliderData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $imageUrl,
        public readonly ?string $photo,
        public readonly ?string $url,
        public readonly ?CategorySummaryData $category,
        public readonly ?TagData $tag,
        /**
         * Legacy compatibility aliases for clients still expecting flattened relation ids.
         * Remove these after consumers migrate to the nested `category` and `tag` objects.
         */
        public readonly ?int $categoryId,
        public readonly ?int $tagId,
    ) {}

    public static function fromModel(Slider $slider): self
    {
        $imageUrl = $slider->getFirstMediaUrl('image') ?: null;

        return new self(
            id: (int) $slider->getKey(),
            imageUrl: $imageUrl,
            photo: $imageUrl,
            url: $slider->url,
            category: $slider->relationLoaded('category') && null !== $slider->category
                ? CategorySummaryData::fromModel($slider->category)
                : null,
            tag: $slider->relationLoaded('tag') && null !== $slider->tag
                ? TagData::fromModel($slider->tag)
                : null,
            categoryId: $slider->category_id,
            tagId: $slider->tag_id,
        );
    }
}
