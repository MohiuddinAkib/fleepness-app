<?php

declare(strict_types=1);

namespace App\Data\Response\Livestream;

use App\Data\LivestreamData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class LivestreamSessionResponseData extends Data
{
    public function __construct(
        public readonly LivestreamData $data,
        public readonly ?string $token,
    ) {}
}
