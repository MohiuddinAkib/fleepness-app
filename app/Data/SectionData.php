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
        public readonly ?string $title,
        public readonly SectionType $type,
        public readonly ?string $description,
        public readonly ?string $backgroundImageUrl,
        public readonly ?string $bannerImageUrl,
        /** @var list<array{id: int, title: ?string, image_url: ?string, sort_order: int}> */
        public readonly array $items,
    ) {}

    public static function fromModel(Section $section): self
    {
        return new self(
            id: (int) $section->getKey(),
            name: $section->name,
            title: $section->title,
            type: $section->type,
            description: $section->description,
            backgroundImageUrl: $section->getFirstMediaUrl('background_image') ?: null,
            bannerImageUrl: $section->getFirstMediaUrl('banner_image') ?: null,
            items: $section->relationLoaded('items')
                ? $section->items->map(fn ($item) => [
                    'id' => (int) $item->getKey(),
                    'title' => $item->title,
                    'image_url' => $item->getFirstMediaUrl('image') ?: null,
                    'sort_order' => (int) $item->sort_order,
                ])->values()->all()
                : [],
        );
    }
}
