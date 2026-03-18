<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class StorePaymentAccountData extends Data
{
    public function __construct(
        public readonly int $paymentMethodId,
        public readonly string $accountNumber,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'account_number' => ['required', 'string', 'max:50'],
        ];
    }
}
