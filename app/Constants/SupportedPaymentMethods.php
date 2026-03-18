<?php

declare(strict_types=1);

namespace App\Constants;

enum SupportedPaymentMethods: string
{
    case COD = 'cash-in-hand';
    case SSLCOMMERZ = 'sslcommerz';
}
