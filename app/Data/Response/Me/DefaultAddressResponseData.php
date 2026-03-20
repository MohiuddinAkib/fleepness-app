<?php

declare(strict_types=1);

namespace App\Data\Response\Me;

use App\Data\AddressData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class DefaultAddressResponseData extends Data
{
    public function __construct(
        public readonly ?AddressData $defaultAddress,
        public readonly ?AddressData $data,
    ) {}
}
