<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\SizeTemplate;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class SizeTemplateData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $vendorProfileId,
        public readonly string $name,
        /** @var list<SizeTemplateItemData> */
        public readonly array $items,
    ) {}

    public static function fromModel(SizeTemplate $sizeTemplate): self
    {
        return new self(
            id: (int) $sizeTemplate->getKey(),
            vendorProfileId: (int) $sizeTemplate->vendor_profile_id,
            name: $sizeTemplate->name,
            items: $sizeTemplate->relationLoaded('items')
                ? $sizeTemplate->items->map(fn ($item) => SizeTemplateItemData::fromModel($item))->values()->all()
                : [],
        );
    }
}
