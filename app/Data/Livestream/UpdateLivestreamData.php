<?php

declare(strict_types=1);

namespace App\Data\Livestream;

use Spatie\LaravelData\Data;
use App\Enums\LivestreamStatus;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Optional;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;

#[MapInputName(SnakeCaseMapper::class)]
class UpdateLivestreamData extends Data
{
    public function __construct(
        #[Min(3)]
        public readonly Optional|string $title,
        public readonly null|Optional|string $description,
        public readonly null|Optional|string $scheduledAt,
        public readonly LivestreamStatus|Optional $status,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'status' => [
                'sometimes',
                new Enum(LivestreamStatus::class),
                Rule::in([
                    LivestreamStatus::Started->value,
                    LivestreamStatus::Finished->value,
                ]),
            ],
        ];
    }
}
