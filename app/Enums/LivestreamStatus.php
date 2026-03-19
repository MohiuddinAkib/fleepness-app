<?php

declare(strict_types=1);

namespace App\Enums;

enum LivestreamStatus: string
{
    case Scheduled = 'scheduled';
    case Started = 'started';
    case Finished = 'finished';

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
