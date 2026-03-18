<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Livestream;
use Spatie\LaravelData\Data;
use App\Enums\LivestreamStatus;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class LivestreamData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $description,
        public readonly LivestreamStatus $status,
        public readonly ?string $thumbnailUrl,
        public readonly int $viewerCount,
        public readonly ?string $scheduledAt,
        public readonly ?string $startedAt,
        public readonly ?string $endedAt,
        public readonly ?VendorProfileData $vendorProfile,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(Livestream $livestream): self
    {
        return new self(
            id: (int) $livestream->getKey(),
            title: $livestream->title,
            description: $livestream->description,
            status: $livestream->status,
            thumbnailUrl: $livestream->getFirstMediaUrl('thumbnail') ?: null,
            viewerCount: (int) ($livestream->viewer_count ?? 0),
            scheduledAt: $livestream->scheduled_at?->toISOString(),
            startedAt: $livestream->started_at?->toISOString(),
            endedAt: $livestream->ended_at?->toISOString(),
            vendorProfile: $livestream->relationLoaded('vendorProfile')
                ? VendorProfileData::fromModel($livestream->vendorProfile)
                : null,
            createdAt: $livestream->created_at->toISOString(),
        );
    }
}
