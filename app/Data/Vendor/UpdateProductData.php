<?php

declare(strict_types=1);

namespace App\Data\Vendor;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class UpdateProductData extends Data
{
    public function __construct(
        #[Max(255)]
        public readonly Optional|string $name,

        public readonly int|Optional $quantity,

        public readonly float|Optional $sellingPrice,

        public readonly null|int|Optional $categoryId = null,

        public readonly null|float|Optional $discountPrice = null,

        public readonly null|Optional|string $shortDescription = null,

        public readonly null|Optional|string $description = null,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'quantity' => ['sometimes', 'integer', 'min:0'],
            'selling_price' => ['sometimes', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }
}
