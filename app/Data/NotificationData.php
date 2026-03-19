<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;
use Illuminate\Notifications\DatabaseNotification;

#[MapOutputName(SnakeCaseMapper::class)]
class NotificationData extends Data
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly array $data,
        public readonly ?string $readAt,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(DatabaseNotification $notification): self
    {
        return new self(
            id: (string) $notification->getKey(),
            type: $notification->type,
            data: is_array($notification->data) ? $notification->data : [],
            readAt: $notification->read_at?->toISOString(),
            createdAt: $notification->created_at->toISOString(),
        );
    }
}
