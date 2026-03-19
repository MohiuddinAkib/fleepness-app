<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class AccountSummaryData extends Data
{
    public function __construct(
        public readonly int $userId,
        public readonly string $name,
        public readonly string $role,
        public readonly ?string $status,
    ) {}
}
