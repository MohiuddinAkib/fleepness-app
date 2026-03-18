<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class UpdateVendorProfileData extends Data
{
    public function __construct(
        #[Max(255)]
        public readonly Optional|string $shopName,

        public readonly Optional|string $description,

        #[Max(255)]
        public readonly Optional|string $pickupLocation,

        public readonly int|Optional $shopCategoryId,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'shop_category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
        ];
    }
}
