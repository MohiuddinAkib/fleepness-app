<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductApprovalStatus: string implements HasColor, HasIcon, HasLabel
{
    case Approved = '1';
    case Pending = '0';

    public static function fromBoolean(bool $isApproved): self
    {
        return $isApproved ? self::Approved : self::Pending;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Approved => 'Approved',
            self::Pending => 'Pending',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Approved => 'success',
            self::Pending => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Approved => 'heroicon-o-check-circle',
            self::Pending => 'heroicon-o-clock',
        };
    }
}
