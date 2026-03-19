<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListNotificationsData extends Data
{
    public function __construct(
        public readonly int $perPage = 15,
        public readonly ?string $type = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'type' => ['nullable', 'in:read,unread'],
        ];
    }
}
