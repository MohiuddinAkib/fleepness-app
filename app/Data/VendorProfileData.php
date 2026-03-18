<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\VendorStatus;
use Spatie\LaravelData\Data;
use App\Models\VendorProfile;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class VendorProfileData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $shopName,
        public readonly ?string $description,
        public readonly ?string $bannerImagePath,
        public readonly ?string $coverImagePath,
        public readonly ?string $pickupLocation,
        public readonly VendorStatus $status,
        public readonly string $balance,
        public readonly ?UserData $user,
    ) {}

    public static function fromModel(VendorProfile $vendorProfile): self
    {
        return new self(
            id: (int) $vendorProfile->getKey(),
            shopName: $vendorProfile->shop_name,
            description: $vendorProfile->description,
            bannerImagePath: $vendorProfile->banner_image_path,
            coverImagePath: $vendorProfile->cover_image_path,
            pickupLocation: $vendorProfile->pickup_location,
            status: $vendorProfile->status,
            balance: (string) ($vendorProfile->balance ?? '0.00'),
            user: $vendorProfile->relationLoaded('user')
                ? UserData::fromModel($vendorProfile->user)
                : null,
        );
    }
}
