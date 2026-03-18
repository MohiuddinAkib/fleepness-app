<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\ProductReview;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class ProductReviewData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $rating,
        public readonly ?string $review,
        public readonly ?UserData $user,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(ProductReview $review): self
    {
        return new self(
            id: (int) $review->getKey(),
            rating: (int) $review->rating,
            review: $review->review,
            user: $review->relationLoaded('user')
                ? UserData::fromModel($review->user)
                : null,
            createdAt: $review->created_at->toISOString(),
        );
    }
}
