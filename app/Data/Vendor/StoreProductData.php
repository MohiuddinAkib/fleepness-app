<?php

declare(strict_types=1);

namespace App\Data\Vendor;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class StoreProductData extends Data
{
    public function __construct(
        #[Max(255)]
        public readonly string $name,

        public readonly int $quantity,

        public readonly float $sellingPrice,

        public readonly ?int $categoryId = null,

        public readonly ?float $discountPrice = null,

        public readonly ?string $shortDescription = null,

        public readonly ?string $description = null,

        public readonly ?int $sizeTemplateId = null,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'size_template_id' => ['nullable', 'integer', 'exists:size_templates,id'],
        ];
    }
}
