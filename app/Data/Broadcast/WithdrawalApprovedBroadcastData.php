<?php

declare(strict_types=1);

namespace App\Data\Broadcast;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class WithdrawalApprovedBroadcastData extends Data
{
    public function __construct(
        public readonly string $reference,
        public readonly string $amount,
    ) {}
}
