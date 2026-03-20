<?php

declare(strict_types=1);

namespace App\Data\Response\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorApplicationStatusResponseData extends Data
{
    public function __construct(
        public readonly VendorApplicationStatusData $data,
    ) {}
}
