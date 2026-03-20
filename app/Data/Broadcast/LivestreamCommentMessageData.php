<?php

declare(strict_types=1);

namespace App\Data\Broadcast;

use Spatie\LaravelData\Data;
use App\Models\LivestreamComment;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class LivestreamCommentMessageData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
    ) {}

    public static function fromModel(LivestreamComment $comment): self
    {
        return new self(
            id: (int) $comment->getKey(),
            title: $comment->comment,
        );
    }
}
