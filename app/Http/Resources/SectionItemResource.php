<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\SectionItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SectionItem
 */
class SectionItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'image' => $this->image,
            'title' => $this->title,
            'bio' => $this->bio,
            'index' => $this->index,
            'visibility' => (bool) $this->visibility,
            'tag_id' => $this->whenLoaded('tag', fn () => $this->tag?->getKey()),
            'tag_name' => $this->whenLoaded('tag', fn () => $this->tag?->name),
            'products' => $this->whenLoaded('products', fn () => ProductResource::collection($this->products)),
        ];
    }
}
