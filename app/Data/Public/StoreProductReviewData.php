<?php

declare(strict_types=1);

namespace App\Data\Public;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\Nullable;

#[MapName(SnakeCaseMapper::class)]
class StoreProductReviewData extends Data
{
    public function __construct(
        #[Between(1, 5)]
        public readonly int $rating,
        #[Nullable]
        public readonly ?string $review = null,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
