<?php

declare(strict_types=1);

namespace App\Filament\Resources\Fees\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\Fees\FeeResource;

class ManageFees extends ManageRecords
{
    protected static string $resource = FeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
