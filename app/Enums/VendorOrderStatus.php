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
}
