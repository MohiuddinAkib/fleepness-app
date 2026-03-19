<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\Livestreams\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Vendor\Resources\Livestreams\LivestreamResource;

class CreateLivestream extends CreateRecord
{
    protected static string $resource = LivestreamResource::class;
}
