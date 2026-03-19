<?php

declare(strict_types=1);

namespace App\Data\Livestream;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;

#[MapName(SnakeCaseMapper::class)]
class UpdateCommentData extends Data
{
    public function __construct(
        #[Min(1)]
        public readonly string $comment,
    ) {}
}
