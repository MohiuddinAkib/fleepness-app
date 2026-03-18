<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class UpdateProfileData extends Data
{
    public function __construct(
        #[Max(100)]
        public readonly Optional|string $name,

        #[Max(255)]
        public readonly Optional|string $email,
    ) {}
}
