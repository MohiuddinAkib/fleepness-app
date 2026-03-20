<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule;
use App\Enums\VendorOrderStatus;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class ListVendorOrdersData extends Data
{
    public function __construct(
        public readonly ?VendorOrderStatus $status = null,
        public readonly int $page = 1,
        public readonly int $perPage = 20,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'status' => ['nullable', Rule::enum(VendorOrderStatus::class)],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
