<?php

declare(strict_types=1);

namespace App\Constants;

enum ProductStatuses: string
{
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
