<?php

namespace App\Http\Resources;

use App\Models\SellerOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SellerOrder
 */
class SellerOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'seller_order_code' => $this->seller_order_code,
            'status' => $this->status,
            'status_message' => $this->status_message,
            'product_cost' => $this->product_cost,
            'commission' => $this->commission,
            'vat' => $this->vat,
            'delivery_fee' => $this->delivery_fee,
            'delivery_start_time' => $this->delivery_start_time,
            'delivery_end_time' => $this->delivery_end_time,
            'rider_assigned' => $this->rider_assigned,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'seller' => $this->whenLoaded('seller', fn () => UserResource::make($this->seller)),
            'customer' => $this->whenLoaded('customer', fn () => UserResource::make($this->customer)),
            'items' => $this->whenLoaded('items', fn () => OrderItemResource::collection($this->items)),
            'order' => $this->whenLoaded('order', fn () => OrderResource::make($this->order)),
        ];
    }
}
