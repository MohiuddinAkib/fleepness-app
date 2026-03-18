<?php

declare(strict_types=1);

namespace App\Enums;

enum LivestreamStatus: string
{
    case Scheduled = 'scheduled';
    case Started = 'started';
    case Finished = 'finished';
}
