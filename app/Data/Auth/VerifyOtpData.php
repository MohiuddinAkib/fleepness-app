<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class VerifyOtpData extends Data
{
    public function __construct(
        public readonly string $phoneNumber,
        public readonly string $otp,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'phone_number' => ['required', 'string', 'exists:users,phone_number'],
            'otp' => ['required', 'digits:4'],
        ];
    }
}
