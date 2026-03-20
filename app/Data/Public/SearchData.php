<?php

declare(strict_types=1);

namespace App\Data\Public;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class SearchData extends Data
{
    public function __construct(
        public readonly ?string $q = null,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
        ];
    }
}
