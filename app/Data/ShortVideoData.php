<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\ShortVideo;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class ShortVideoData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $videoUrl,
        public readonly ?string $thumbnailUrl,
        public readonly int $likesCount,
        public readonly ?VendorProfileData $vendorProfile,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(ShortVideo $shortVideo): self
    {
        return new self(
            id: (int) $shortVideo->getKey(),
            title: $shortVideo->title,
            description: $shortVideo->description,
            videoUrl: $shortVideo->getFirstMediaUrl('video'),
            thumbnailUrl: $shortVideo->getFirstMediaUrl('thumbnail') ?: null,
            likesCount: (int) ($shortVideo->likes_count ?? 0),
            vendorProfile: $shortVideo->relationLoaded('vendorProfile')
                ? VendorProfileData::fromModel($shortVideo->vendorProfile)
                : null,
            createdAt: $shortVideo->created_at->toISOString(),
        );
    }
}
