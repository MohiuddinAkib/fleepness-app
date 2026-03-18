<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\VendorOrder;
use Spatie\LaravelData\Data;
use App\Enums\VendorOrderStatus;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorOrderData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $orderNumber,
        public readonly VendorOrderStatus $status,
        public readonly string $productTotal,
        public readonly string $commission,
        public readonly string $vat,
        public readonly string $deliveryFee,
        public readonly string $balance,
        public readonly ?VendorProfileData $vendorProfile,
        /** @var list<VendorOrderItemData> */
        public readonly array $items,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(VendorOrder $vendorOrder): self
    {
        return new self(
            id: (int) $vendorOrder->getKey(),
            orderNumber: $vendorOrder->order_number,
            status: $vendorOrder->status,
            productTotal: (string) $vendorOrder->product_total,
            commission: (string) $vendorOrder->commission,
            vat: (string) $vendorOrder->vat,
            deliveryFee: (string) $vendorOrder->delivery_fee,
            balance: (string) $vendorOrder->balance,
            vendorProfile: $vendorOrder->relationLoaded('vendorProfile')
                ? VendorProfileData::fromModel($vendorOrder->vendorProfile)
                : null,
            items: $vendorOrder->relationLoaded('items')
                ? $vendorOrder->items->map(fn ($i) => VendorOrderItemData::fromModel($i))->all()
                : [],
            createdAt: $vendorOrder->created_at->toISOString(),
        );
    }
}
