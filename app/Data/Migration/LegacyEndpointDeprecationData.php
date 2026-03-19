<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class LegacyEndpointDeprecationData extends Data
{
    /**
     * @param  list<string>  $methods
     * @param  list<string>  $replacements
     */
    public function __construct(
        public readonly string $key,
        public readonly string $legacyPath,
        public readonly array $methods,
        public readonly array $replacements,
    ) {}
}
