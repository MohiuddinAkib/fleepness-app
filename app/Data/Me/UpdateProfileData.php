<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class UpdateProfileData extends Data
{
    public function __construct(
        #[Max(100)]
        public readonly Optional|string $name,

        #[Max(255)]
        public readonly Optional|string $email,

        #[Max(30)]
        public readonly Optional|string $phoneNumber,

        public readonly Optional|UploadedFile $bannerImage,

        public readonly null|Optional|UploadedFile $coverImage,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'phone_number' => ['sometimes', 'string', 'max:30'],
            'banner_image' => ['sometimes', File::image()->max(5 * 1024)],
            'cover_image' => ['sometimes', 'nullable', File::image()->max(5 * 1024)],
        ];
    }
}
