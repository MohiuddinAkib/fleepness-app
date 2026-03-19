<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sliders\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Sliders\SliderResource;

class CreateSlider extends CreateRecord
{
    protected static string $resource = SliderResource::class;
}
