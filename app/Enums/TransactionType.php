<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TransactionType: string implements HasColor, HasIcon, HasLabel
{
    case Deposit = 'deposit';
    case Withdrawal = 'withdrawal';

    public function getLabel(): string
    {
        return match ($this) {
            self::Deposit => 'Deposit',
            self::Withdrawal => 'Withdrawal',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Deposit => 'success',
            self::Withdrawal => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Deposit => 'heroicon-o-arrow-down-tray',
            self::Withdrawal => 'heroicon-o-arrow-up-tray',
        };
    }

    public function isDeposit(): bool
    {
        return self::Deposit === $this;
    }

    public function isWithdrawal(): bool
    {
        return self::Withdrawal === $this;
    }
}
