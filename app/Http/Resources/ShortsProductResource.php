<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ShortsProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'name' => $this->name,
            'short_description' => $this->short_description,
            'selling_price' => $this->selling_price,
            'discount_price' => $this->discount_price,
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($img) => asset($img->path))),
        ];
    }
}
