<?php

declare(strict_types=1);

namespace App\Data\Broadcast;

use Spatie\LaravelData\Data;
use App\Data\VendorOrderData;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorOrderBroadcastData extends Data
{
    public function __construct(
        public readonly VendorOrderData $vendorOrder,
    ) {}
}
