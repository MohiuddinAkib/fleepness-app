<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\DeliveryOption;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class DeliveryOptionData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $estimatedMinutes,
        public readonly string $fee,
    ) {}

    public static function fromModel(DeliveryOption $deliveryOption): self
    {
        return new self(
            id: (int) $deliveryOption->getKey(),
            name: $deliveryOption->name,
            description: $deliveryOption->description,
            estimatedMinutes: (int) $deliveryOption->estimated_minutes,
            fee: (string) $deliveryOption->fee,
        );
    }
}
