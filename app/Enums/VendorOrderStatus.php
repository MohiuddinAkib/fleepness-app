<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VendorOrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Packaging = 'packaging';
    case OnTheWay = 'on_the_way';
    case Delivered = 'delivered';
    case Delayed = 'delayed';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Packaging => 'Packaging',
            self::OnTheWay => 'On the Way',
            self::Delivered => 'Delivered',
            self::Delayed => 'Delayed',
            self::Rejected => 'Rejected',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Packaging => 'info',
            self::OnTheWay => 'primary',
            self::Delivered => 'success',
            self::Delayed => 'warning',
            self::Rejected => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Packaging => 'heroicon-o-archive-box',
            self::OnTheWay => 'heroicon-o-truck',
            self::Delivered => 'heroicon-o-check-circle',
            self::Delayed => 'heroicon-o-exclamation-triangle',
            self::Rejected => 'heroicon-o-x-circle',
        };
    }

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

    public function shouldBroadcastCustomerUpdate(): bool
    {
        return ! $this->isPending();
    }
}
