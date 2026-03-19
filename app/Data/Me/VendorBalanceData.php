<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorBalanceData extends Data
{
    public function __construct(
        public readonly string $balance,
        public readonly string $totalSales,
        public readonly string $withdrawnAmount,
        public readonly string $dailyBalance,
        public readonly string $weeklyBalance,
        public readonly string $monthlyBalance,
        public readonly string $lifetimeBalance,
    ) {}
}
