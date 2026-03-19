<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LivestreamStatus: string implements HasColor, HasIcon, HasLabel
{
    case Scheduled = 'scheduled';
    case Started = 'started';
    case Finished = 'finished';

    public function getLabel(): string
    {
        return match ($this) {
            self::Scheduled => 'Scheduled',
            self::Started => 'Live',
            self::Finished => 'Finished',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Scheduled => 'info',
            self::Started => 'success',
            self::Finished => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Scheduled => 'heroicon-o-calendar',
            self::Started => 'heroicon-o-signal',
            self::Finished => 'heroicon-o-stop-circle',
        };
    }

    public function isScheduled(): bool
    {
        return self::Scheduled === $this;
    }

    public function isStarted(): bool
    {
        return self::Started === $this;
    }

    public function isFinished(): bool
    {
        return self::Finished === $this;
    }
}
