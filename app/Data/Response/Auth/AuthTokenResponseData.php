<?php

declare(strict_types=1);

namespace App\Data\Response\Auth;

use App\Data\UserData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class AuthTokenResponseData extends Data
{
    public function __construct(
        public readonly string $message,
        public readonly string $token,
        public readonly UserData $user,
    ) {}
}
