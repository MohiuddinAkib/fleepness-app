<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListOrdersData extends Data
{
    public function __construct(
        public readonly ?string $orderCode = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'order_code' => ['nullable', 'string', 'max:255'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
