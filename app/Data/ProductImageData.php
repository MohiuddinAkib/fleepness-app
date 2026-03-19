<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[MapOutputName(SnakeCaseMapper::class)]
class ProductImageData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $path,
        public readonly ?string $altText,
    ) {}

    public static function fromMedia(Media $media): self
    {
        return new self(
            id: (int) $media->getKey(),
            path: $media->getUrl(),
            altText: $media->getCustomProperty('alt_text') ?: null,
        );
    }
}
