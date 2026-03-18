<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class SendOtpData extends Data
{
    public function __construct(
        public readonly string $phoneNumber,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'phone_number' => ['required', 'string', 'digits:11', 'exists:users,phone_number'],
        ];
    }
}
