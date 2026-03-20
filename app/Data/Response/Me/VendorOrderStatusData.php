<?php

declare(strict_types=1);

namespace App\Data\Response\Me;

use Spatie\LaravelData\Data;
use App\Enums\VendorOrderStatus;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorOrderStatusData extends Data
{
    public function __construct(
        public readonly VendorOrderStatus $status,
    ) {}
}
