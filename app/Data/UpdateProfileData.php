<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Constants\GenderTypes;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Exists;
use AhmedAliraqi\LaravelMediaUploader\Entities\TemporaryFile;

#[MapName(SnakeCaseMapper::class)]
class UpdateProfileData extends Data
{
    public function __construct(
        public Optional|string $name,
        #[Exists(TemporaryFile::class, 'token')]
        public Optional|string $profilePicture,
        public GenderTypes|Optional $gender,
        public Optional|string $address,
        public Optional|string $phonenumber,
    ) {}
}
