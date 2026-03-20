<?php

declare(strict_types=1);

namespace App\Data\Broadcast;

use App\Enums\VendorStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorApplicationStatusBroadcastData extends Data
{
    public function __construct(
        public readonly VendorStatus $status,
        public readonly string $message,
    ) {}
}
