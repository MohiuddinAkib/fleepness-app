<?php

declare(strict_types=1);

namespace App\Filament\Resources\PaymentMethods\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PaymentMethods\PaymentMethodResource;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}
