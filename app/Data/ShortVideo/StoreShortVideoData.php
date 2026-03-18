<?php

declare(strict_types=1);

namespace App\Data\ShortVideo;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;

#[MapInputName(SnakeCaseMapper::class)]
class StoreShortVideoData extends Data
{
    public function __construct(
        #[Min(3)]
        public readonly string $title,
        public readonly ?string $description,
    ) {}
}
