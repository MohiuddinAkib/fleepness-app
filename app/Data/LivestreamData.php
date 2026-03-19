<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Livestream;
use Spatie\LaravelData\Data;
use App\Enums\LivestreamStatus;
use Illuminate\Support\Traits\Conditionable;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class LivestreamData extends Data
{
    use Conditionable;

    /**
     * @param  list<array<string, mixed>>  $recordings
     * @param  list<array<string, mixed>>  $thumbnails
     */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $roomName,
        public readonly LivestreamStatus $status,
        public readonly ?string $thumbnailUrl,
        public readonly int $viewerCount,
        public readonly ?int $totalDuration,
        public readonly ?string $scheduledAt,
        public readonly ?string $startedAt,
        public readonly ?string $endedAt,
        public readonly array $recordings,
        public readonly array $thumbnails,
        public readonly ?VendorProfileData $vendorProfile,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(Livestream $livestream): self
    {
        return new self(
            id: (int) $livestream->getKey(),
            title: $livestream->title,
            description: $livestream->description,
            roomName: $livestream->room_name,
            status: $livestream->status,
            thumbnailUrl: $livestream->getFirstMediaUrl('thumbnail') ?: null,
            viewerCount: (int) ($livestream->viewer_count ?? 0),
            totalDuration: null === $livestream->total_duration ? null : (int) $livestream->total_duration,
            scheduledAt: $livestream->scheduled_at?->toISOString(),
            startedAt: $livestream->started_at?->toISOString(),
            endedAt: $livestream->ended_at?->toISOString(),
            recordings: is_array($livestream->recordings) ? $livestream->recordings : [],
            thumbnails: is_array($livestream->thumbnails) ? $livestream->thumbnails : [],
            vendorProfile: $livestream->relationLoaded('vendorProfile')
                ? VendorProfileData::fromModel($livestream->vendorProfile)
                : null,
            createdAt: $livestream->created_at->toISOString(),
        );
    }
}
