<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Order;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class OrderData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $orderNumber,
        public readonly bool $isMultiVendor,
        public readonly int $vendorCount,
        public readonly string $productTotal,
        public readonly string $deliveryFee,
        public readonly string $platformFee,
        public readonly string $vat,
        public readonly string $commission,
        public readonly string $grandTotal,
        public readonly bool $isCompleted,
        /** @var list<VendorOrderData> */
        public readonly array $vendorOrders,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(Order $order): self
    {
        return new self(
            id: (int) $order->getKey(),
            orderNumber: $order->order_number,
            isMultiVendor: (bool) $order->is_multi_vendor,
            vendorCount: (int) $order->vendor_count,
            productTotal: (string) $order->product_total,
            deliveryFee: (string) $order->delivery_fee,
            platformFee: (string) $order->platform_fee,
            vat: (string) $order->vat,
            commission: (string) $order->commission,
            grandTotal: (string) $order->grand_total,
            isCompleted: (bool) $order->is_completed,
            vendorOrders: $order->relationLoaded('vendorOrders')
                ? $order->vendorOrders->map(fn ($vo) => VendorOrderData::fromModel($vo))->all()
                : [],
            createdAt: $order->created_at->toISOString(),
        );
    }
}
