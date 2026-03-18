<?php

namespace App\Http\Resources;

use App\Models\ShortVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ShortVideo
 */
class ShortVideoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'title' => $this->title,
            'video' => $this->video,
            'likes_count' => $this->likes_count ?? 0,
            'created_at' => $this->created_at,
            'products' => $this->whenLoaded('products', fn () => ShortsProductResource::collection($this->products)),
        ];
    }
}
