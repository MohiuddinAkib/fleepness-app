<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Transaction;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class TransactionData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $reference,
        public readonly string $amount,
        public readonly string $type,
        public readonly string $status,
        public readonly ?string $note,
        public readonly string $createdAt,
    ) {}

    public static function fromModel(Transaction $transaction): self
    {
        return new self(
            id: (int) $transaction->getKey(),
            reference: $transaction->reference,
            amount: (string) $transaction->amount,
            type: $transaction->type->value,
            status: $transaction->status->value,
            note: $transaction->note,
            createdAt: $transaction->created_at->toISOString(),
        );
    }
}
