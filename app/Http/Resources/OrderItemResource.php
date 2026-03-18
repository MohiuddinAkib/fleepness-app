<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\SellerOrderItem;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SellerOrderItem
 */
class OrderItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'size' => $this->size,
            'quantity' => $this->quantity,
            'total_cost' => $this->total_cost,
            'product' => $this->whenLoaded('product', fn () => ProductResource::make($this->product)),
        ];
    }
}
