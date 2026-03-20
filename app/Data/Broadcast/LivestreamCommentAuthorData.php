<?php

declare(strict_types=1);

namespace App\Data\Broadcast;

use App\Models\User;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class LivestreamCommentAuthorData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $avatar,
        public readonly string $phoneNumber,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: (int) $user->getKey(),
            name: $user->name,
            email: $user->email,
            avatar: $user->getFirstMediaUrl('cover_image') ?: null,
            phoneNumber: $user->phone_number,
        );
    }
}
