<?php

declare(strict_types=1);

namespace App\Data\Livestream;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use App\Enums\LivestreamStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\Validation\DateFormat;

#[MapInputName(SnakeCaseMapper::class)]
class UpdateLivestreamData extends Data
{
    public function __construct(
        #[Min(3)]
        public readonly Optional|string $title,
        public readonly null|Optional|string $description,
        #[DateFormat('Y-m-d H:i:s'), WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s', type: CarbonImmutable::class)]
        public readonly null|CarbonImmutable|Optional $scheduledAt,
        public readonly LivestreamStatus|Optional $status,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'status' => [
                'sometimes',
                new Enum(LivestreamStatus::class),
            ],
        ];
    }
}
