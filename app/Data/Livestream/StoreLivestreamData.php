<?php

declare(strict_types=1);

namespace App\Data\Livestream;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;

#[MapInputName(SnakeCaseMapper::class)]
class StoreLivestreamData extends Data
{
    public function __construct(
        #[Min(3)]
        public readonly string $title,
        public readonly Optional|string $description,
        public readonly Optional|string $scheduledAt,
    ) {}
}
