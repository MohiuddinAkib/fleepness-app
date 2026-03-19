<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function isPending(): bool
    {
        return self::Pending === $this;
    }

    public function isApproved(): bool
    {
        return self::Approved === $this;
    }

    public function isRejected(): bool
    {
        return self::Rejected === $this;
    }
}
