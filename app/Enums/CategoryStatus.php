<?php

declare(strict_types=1);

namespace App\Enums;

enum CategoryStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public function isActive(): bool
    {
        return self::Active === $this;
    }

    public function isInactive(): bool
    {
        return self::Inactive === $this;
    }
}
