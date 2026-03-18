<?php

namespace App\Http\Resources;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CartItem
 */
class CartItemResource extends JsonResource
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
            'quantity' => $this->quantity,
            'selected' => $this->selected,
            'product' => $this->whenLoaded('product', fn () => ProductResource::make($this->product)),
            'size' => $this->whenLoaded('size', fn () => $this->size ? ProductSizeResource::make($this->size) : null),
        ];
    }
}
