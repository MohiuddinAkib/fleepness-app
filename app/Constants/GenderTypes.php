<?php

declare(strict_types=1);

namespace App\Constants;

enum GenderTypes: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}
