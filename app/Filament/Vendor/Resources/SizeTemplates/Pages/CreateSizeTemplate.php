<?php

declare(strict_types=1);

namespace App\Filament\Vendor\Resources\SizeTemplates\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Vendor\Resources\SizeTemplates\SizeTemplateResource;

class CreateSizeTemplate extends CreateRecord
{
    protected static string $resource = SizeTemplateResource::class;
}
