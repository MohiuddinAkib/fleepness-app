<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\VendorOrderItem;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorOrderItemData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $quantity,
        public readonly string $unitPrice,
        public readonly string $totalPrice,
        public readonly ?ProductData $product,
    ) {}

    public static function fromModel(VendorOrderItem $item): self
    {
        return new self(
            id: (int) $item->getKey(),
            quantity: (int) $item->quantity,
            unitPrice: (string) $item->unit_price,
            totalPrice: (string) $item->total_price,
            product: $item->relationLoaded('product')
                ? ProductData::fromModel($item->product)
                : null,
        );
    }
}
