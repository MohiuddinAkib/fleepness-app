<?php

namespace App\Filament\Resources\Transactions\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Transactions\TransactionResource;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
