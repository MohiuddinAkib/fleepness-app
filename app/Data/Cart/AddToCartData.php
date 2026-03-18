<?php

declare(strict_types=1);

namespace App\Data\Cart;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class AddToCartData extends Data
{
    public function __construct(
        public readonly int $productId,
        public readonly ?int $productVariantId,
        public readonly int $quantity,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
