<?php

declare(strict_types=1);

namespace App\Data\Dto;

use Spatie\LaravelData\Data;
use App\Models\DeliveryModel;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Exists;

#[MapName(SnakeCaseMapper::class)]
class StoreOrderData extends Data
{
    public function __construct(
        #[Exists(DeliveryModel::class, 'id')]
        public readonly int $deliveryModelId,
    ) {}
}
