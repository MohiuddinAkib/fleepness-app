<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class VendorApplicationData extends Data
{
    public function __construct(
        #[Max(255)]
        public readonly string $shopName,

        public readonly ?string $description = null,

        public readonly ?int $shopCategoryId = null,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
        ];
    }
}
