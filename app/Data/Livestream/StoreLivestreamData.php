<?php

declare(strict_types=1);

namespace App\Data\Livestream;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\Validation\DateFormat;

#[MapInputName(SnakeCaseMapper::class)]
class StoreLivestreamData extends Data
{
    public function __construct(
        #[Min(3)]
        public readonly string $title,
        public readonly Optional|string $description,
        #[DateFormat('Y-m-d H:i:s'), WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s', type: CarbonImmutable::class)]
        public readonly CarbonImmutable|Optional $scheduledAt,
    ) {}
}
