<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\VendorReview;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorReviewData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $rating,
        public readonly ?string $comment,
        public readonly ?UserData $user,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(VendorReview $review): self
    {
        return new self(
            id: (int) $review->getKey(),
            rating: (int) $review->rating,
            comment: $review->comment,
            user: $review->relationLoaded('user')
                ? UserData::fromModel($review->user)
                : null,
            createdAt: $review->created_at->toISOString(),
        );
    }
}
