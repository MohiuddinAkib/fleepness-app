<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\SizeTemplateItem;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class SizeTemplateItemData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $sizeTemplateId,
        public readonly string $label,
        public readonly string $value,
    ) {}

    public static function fromModel(SizeTemplateItem $item): self
    {
        return new self(
            id: (int) $item->getKey(),
            sizeTemplateId: (int) $item->size_template_id,
            label: $item->label,
            value: $item->value,
        );
    }
}
