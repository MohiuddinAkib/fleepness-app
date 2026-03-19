<?php

declare(strict_types=1);

namespace App\Data\Dto;

use App\Models\User;
use DateTimeInterface;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Illuminate\Http\UploadedFile;
use App\Constants\LivestreamStatuses;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Enum; // Assuming the media will be associated with a User model
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;

#[MapName(SnakeCaseMapper::class)]
class UpdateLivestremData extends Data
{
    public function __construct(
        public Optional|string $title,
        #[AfterOrEqual('today')]
        public null|DateTimeInterface|Optional $scheduledTime,
        public Optional|UploadedFile $thumbnailPicture, // Now using UploadedFile for media handling
        #[Enum(LivestreamStatuses::class)]
        public LivestreamStatuses|Optional $status,
    ) {}

    /**
     * Handle the file upload and associate it with a model.
     */
    public function handleMedia(User $user): void
    {
        if ($this->thumbnailPicture instanceof UploadedFile) {
            // Upload the file using Spatie Media Library
            $user->addMedia($this->thumbnailPicture)
                ->toMediaCollection('thumbnail_pictures');
        }
    }
}
