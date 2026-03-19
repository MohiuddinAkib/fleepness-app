<?php

declare(strict_types=1);

namespace App\Data\SizeTemplate;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class StoreSizeTemplateData extends Data
{
    public function __construct(
        public readonly string $name,
    ) {}
}
