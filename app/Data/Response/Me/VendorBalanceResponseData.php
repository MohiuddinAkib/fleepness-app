<?php

declare(strict_types=1);

namespace App\Data\Response\Me;

use Spatie\LaravelData\Data;
use App\Data\Me\VendorBalanceData;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorBalanceResponseData extends Data
{
    public function __construct(
        public readonly VendorBalanceData $data,
    ) {}
}
