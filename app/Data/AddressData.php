<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Address;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class AddressData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $label,
        public readonly ?string $formattedAddress,
        public readonly ?string $addressLine1,
        public readonly ?string $addressLine2,
        public readonly ?string $area,
        public readonly ?string $city,
        public readonly ?string $postalCode,
        public readonly ?string $latitude,
        public readonly ?string $longitude,
        public readonly bool $isDefault,
    ) {}

    public static function fromModel(Address $address): self
    {
        return new self(
            id: (int) $address->getKey(),
            label: $address->label,
            formattedAddress: $address->formatted_address,
            addressLine1: $address->address_line_1,
            addressLine2: $address->address_line_2,
            area: $address->area,
            city: $address->city,
            postalCode: $address->postal_code,
            latitude: $address->latitude,
            longitude: $address->longitude,
            isDefault: (bool) $address->is_default,
        );
    }
}
