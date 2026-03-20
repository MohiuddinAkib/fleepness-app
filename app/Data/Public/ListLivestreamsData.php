<?php

declare(strict_types=1);

namespace App\Data\Public;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListLivestreamsData extends Data
{
    public function __construct(
        public readonly ?int $vendorId = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'vendor_id' => ['nullable', 'integer', 'exists:vendor_profiles,id'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
