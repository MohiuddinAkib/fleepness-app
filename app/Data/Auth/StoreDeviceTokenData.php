<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class StoreDeviceTokenData extends Data
{
    public function __construct(
        #[Max(255)]
        public readonly string $token,

        public readonly ?string $platform = null,
    ) {}
}
