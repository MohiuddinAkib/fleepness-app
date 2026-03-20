<?php

declare(strict_types=1);

namespace App\Data\Response\Me;

use App\Enums\VendorStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorApplicationStatusData extends Data
{
    public function __construct(
        public readonly VendorStatus $status,
        public readonly ?string $statusNote,
    ) {}
}
