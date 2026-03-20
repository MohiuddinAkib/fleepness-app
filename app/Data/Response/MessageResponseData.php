<?php

declare(strict_types=1);

namespace App\Data\Response;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class MessageResponseData extends Data
{
    public function __construct(
        public readonly string $message,
    ) {}
}
