<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\ShortVideos\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Vendor\Resources\ShortVideos\ShortVideoResource;

class CreateShortVideo extends CreateRecord
{
    protected static string $resource = ShortVideoResource::class;
}
