<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case Deposit = 'deposit';
    case Withdrawal = 'withdrawal';

    public function isDeposit(): bool
    {
        return self::Deposit === $this;
    }

    public function isWithdrawal(): bool
    {
        return self::Withdrawal === $this;
    }
}
