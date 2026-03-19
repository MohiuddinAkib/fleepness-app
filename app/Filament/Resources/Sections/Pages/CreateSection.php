<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sections\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Sections\SectionResource;

class CreateSection extends CreateRecord
{
    protected static string $resource = SectionResource::class;
}
