<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\PaymentMethod;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class PaymentMethodData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $icon,
        public readonly ?string $iconPath,
        public readonly bool $isActive,
    ) {}

    public static function fromModel(PaymentMethod $paymentMethod): self
    {
        return new self(
            id: (int) $paymentMethod->getKey(),
            name: $paymentMethod->name,
            icon: $paymentMethod->icon_path,
            iconPath: $paymentMethod->icon_path,
            isActive: (bool) $paymentMethod->is_active,
        );
    }
}
