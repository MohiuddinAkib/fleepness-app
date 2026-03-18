<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\CartItem;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class CartItemData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $quantity,
        public readonly bool $isSelected,
        public readonly ?ProductData $product,
        /** @var array{id: int, name: string, price: string}|null */
        public readonly ?array $variant,
    ) {}

    public static function fromModel(CartItem $cartItem): self
    {
        $v = $cartItem->relationLoaded('variant') ? $cartItem->variant : null;

        return new self(
            id: (int) $cartItem->getKey(),
            quantity: (int) $cartItem->quantity,
            isSelected: (bool) $cartItem->is_selected,
            product: $cartItem->relationLoaded('product')
                ? ProductData::fromModel($cartItem->product)
                : null,
            variant: null !== $v
                ? ['id' => (int) $v->getKey(), 'name' => $v->name, 'price' => (string) $v->price]
                : null,
        );
    }
}
