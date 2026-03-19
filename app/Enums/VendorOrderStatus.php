<?php

declare(strict_types=1);

namespace App\Enums;

enum VendorOrderStatus: string
{
    case Pending = 'pending';
    case Packaging = 'packaging';
    case OnTheWay = 'on_the_way';
    case Delivered = 'delivered';
    case Delayed = 'delayed';
    case Rejected = 'rejected';

    public function isPending(): bool
    {
        return self::Pending === $this;
    }

    public function isPackaging(): bool
    {
        return self::Packaging === $this;
    }

    public function isOnTheWay(): bool
    {
        return self::OnTheWay === $this;
    }

    public function isDelivered(): bool
    {
        return self::Delivered === $this;
    }

    public function isDelayed(): bool
    {
        return self::Delayed === $this;
    }

    public function isRejected(): bool
    {
        return self::Rejected === $this;
    }
}
