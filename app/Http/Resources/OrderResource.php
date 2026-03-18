<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'order_code' => $this->order_code,
            'is_multi_seller' => $this->is_multi_seller,
            'total_sellers' => $this->total_sellers,
            'delivery_fee' => $this->delivery_fee,
            'product_cost' => $this->product_cost,
            'commission' => $this->commission,
            'platform_fee' => $this->platform_fee,
            'vat' => $this->vat,
            'grand_total' => $this->grand_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'seller_orders' => $this->whenLoaded('sellerOrders', fn () => SellerOrderResource::collection($this->sellerOrders)),
            'customer' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
        ];
    }
}
