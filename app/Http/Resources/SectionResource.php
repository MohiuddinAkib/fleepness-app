<?php

namespace App\Http\Resources;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Section
 */
class SectionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allowedSectionTypes = ['scrollable_product', 'spotlight_deals', 'lighting_deals', 'search'];
        $showProducts = in_array($this->section_type, $allowedSectionTypes);

        $firstItem = $this->relationLoaded('items') ? $this->items->first() : null;
        $firstTag = $firstItem?->relationLoaded('tag') ? $firstItem->tag : null;

        return [
            $this->getKeyName() => $this->getKey(),
            'section_name' => $this->section_name,
            'section_type' => $this->section_type,
            'section_title' => $this->section_title,
            'bio' => $this->bio,
            'placement_type' => $this->placement_type,
            'index' => $this->index,
            'cat_index' => $this->cat_index,
            'visibility' => (bool) $this->visibility,
            'background_image' => $this->background_image,
            'banner_image' => $this->banner_image,
            'show_products' => $showProducts,
            'tag_id' => $showProducts ? $firstTag?->getKey() : null,
            'tag_name' => $showProducts ? $firstTag?->name : null,
            'category' => $this->whenLoaded('category', fn () => CategoryResource::make($this->category)),
            'items' => $this->whenLoaded('items', fn () => SectionItemResource::collection($this->items)),
        ];
    }
}
