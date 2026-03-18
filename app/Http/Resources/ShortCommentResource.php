<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\ShortsComment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ShortsComment
 */
class ShortCommentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'comment' => $this->comment,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
        ];
    }
}
