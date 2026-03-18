<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class StoreAddressData extends Data
{
    public function __construct(
        #[Max(50)]
        public readonly ?string $label,

        public readonly ?string $formattedAddress,

        #[Max(255)]
        public readonly ?string $addressLine1,

        #[Max(255)]
        public readonly ?string $addressLine2,

        #[Max(100)]
        public readonly ?string $area,

        #[Max(100)]
        public readonly ?string $city,

        #[Max(20)]
        public readonly ?string $postalCode,

        public readonly ?float $latitude,

        public readonly ?float $longitude,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'formatted_address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
