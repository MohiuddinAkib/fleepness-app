<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\LivestreamComment;
use App\Models\ShortVideoComment;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class CommentData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $comment,
        public readonly ?UserData $user,
        public readonly string $createdAt,
    ) {}

    public static function fromShortVideoComment(ShortVideoComment $comment): self
    {
        return new self(
            id: (int) $comment->getKey(),
            comment: $comment->comment,
            user: $comment->relationLoaded('user')
                ? UserData::fromModel($comment->user)
                : null,
            createdAt: $comment->created_at->toISOString(),
        );
    }

    public static function fromLivestreamComment(LivestreamComment $comment): self
    {
        return new self(
            id: (int) $comment->getKey(),
            comment: $comment->comment,
            user: $comment->relationLoaded('user')
                ? UserData::fromModel($comment->user)
                : null,
            createdAt: $comment->created_at->toISOString(),
        );
    }
}
