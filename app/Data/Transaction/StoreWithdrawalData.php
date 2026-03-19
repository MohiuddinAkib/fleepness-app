<?php

declare(strict_types=1);

namespace App\Data\Transaction;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Exists;

#[MapInputName(SnakeCaseMapper::class)]
class StoreWithdrawalData extends Data
{
    public function __construct(
        #[Min(1)]
        public readonly float $amount,
        #[Exists('payment_methods', 'id')]
        public readonly int $paymentMethodId,
        public readonly null|Optional|string $note,
    ) {}
}
