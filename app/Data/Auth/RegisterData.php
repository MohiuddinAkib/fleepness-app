<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class RegisterData extends Data
{
    public function __construct(
        public readonly string $phoneNumber,

        #[Max(100)]
        public readonly ?string $name = null,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'phone_number' => ['required', 'string', 'digits:11', 'unique:users,phone_number'],
        ];
    }

    /** @return array<string, string> */
    public static function messages(): array
    {
        return [
            'phone_number.digits' => 'Phone number must be exactly 11 digits.',
            'phone_number.unique' => 'This phone number is already registered.',
        ];
    }
}
