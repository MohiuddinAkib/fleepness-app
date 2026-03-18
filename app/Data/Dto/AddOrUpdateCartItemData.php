<?php

namespace App\Data\Dto;

use App\Models\Product;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Exists;

#[MapName(SnakeCaseMapper::class)]
class AddOrUpdateCartItemData extends Data
{
    public function __construct(
        #[Exists(Product::class, 'id')]
        public readonly int $productId,

        #[Min(1)]
        public readonly int $quantity,

        public readonly ?int $sizeId,
    ) {}

    public static function rules(): array
    {
        return [
            'size_id' => ['nullable', 'exists:product_sizes,id'],
        ];
    }
}
