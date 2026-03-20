<?php

declare(strict_types=1);

namespace App\Data\Response;

use App\Data\OrderData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class OrderResponseData extends Data
{
    public function __construct(
        public readonly string $message,
        public readonly OrderData $data,
    ) {}
}
