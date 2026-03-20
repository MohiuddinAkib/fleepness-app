<?php

declare(strict_types=1);

namespace App\Data\Public;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListProductsData extends Data
{
    public function __construct(
        public readonly ?string $q = null,
        public readonly ?int $categoryId = null,
        public readonly ?int $tagId = null,
        public readonly ?int $vendorId = null,
        public readonly ?float $minPrice = null,
        public readonly ?float $maxPrice = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'tag_id' => ['nullable', 'integer', 'exists:tags,id'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendor_profiles,id'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'gte:min_price'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
