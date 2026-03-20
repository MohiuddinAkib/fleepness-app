<?php

declare(strict_types=1);

namespace App\Data\Public;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListVendorsData extends Data
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?int $shopCategoryId = null,
        public readonly ?int $similarToVendorId = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'shop_category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
            'similar_to_vendor_id' => ['nullable', 'integer', 'exists:vendor_profiles,id'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
