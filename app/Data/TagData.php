<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Tag;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class TagData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
    ) {}

    public static function fromModel(Tag $tag): self
    {
        return new self(
            id: (int) $tag->getKey(),
            name: $tag->name,
            slug: $tag->slug,
        );
    }
}
