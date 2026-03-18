<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Models\UserPaymentAccount;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class PaymentAccountData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $accountNumber,
        public readonly bool $isPrimary,
        public readonly ?PaymentMethodData $paymentMethod,
    ) {}

    public static function fromModel(UserPaymentAccount $account): self
    {
        return new self(
            id: (int) $account->getKey(),
            accountNumber: $account->account_number,
            isPrimary: (bool) $account->is_primary,
            paymentMethod: $account->relationLoaded('paymentMethod')
                ? PaymentMethodData::fromModel($account->paymentMethod)
                : null,
        );
    }
}
