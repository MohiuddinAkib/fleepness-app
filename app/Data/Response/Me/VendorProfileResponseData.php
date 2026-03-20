<?php

declare(strict_types=1);

namespace App\Data\Response\Me;

use Spatie\LaravelData\Data;
use App\Data\VendorProfileData;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorProfileResponseData extends Data
{
    public function __construct(
        public readonly string $message,
        public readonly VendorProfileData $data,
    ) {}
}
