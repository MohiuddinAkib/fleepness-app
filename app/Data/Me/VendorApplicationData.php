<?php

declare(strict_types=1);

namespace App\Data\Me;

use Spatie\LaravelData\Data;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Max;

#[MapName(SnakeCaseMapper::class)]
class VendorApplicationData extends Data
{
    public function __construct(
        #[Max(100)]
        public readonly ?string $name,

        #[Max(255)]
        public readonly string $shopName,

        public readonly ?string $description = null,

        public readonly ?int $shopCategoryId = null,

        #[Max(255)]
        public readonly ?string $email = null,

        #[Max(30)]
        public readonly ?string $phoneNumber = null,

        #[Max(255)]
        public readonly ?string $pickupLocation = null,

        public readonly ?UploadedFile $bannerImage = null,

        public readonly ?UploadedFile $coverImage = null,

        public readonly ?string $paymentNumber = null,

        /** @var array<string, int>|null */
        public readonly ?array $payments = null,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'banner_image' => ['nullable', File::image()->max(5 * 1024)],
            'cover_image' => ['nullable', File::image()->max(5 * 1024)],
            'payment_number' => ['nullable', 'string', 'max:50'],
            'payments' => ['nullable', 'array'],
            'payments.*' => ['integer', Rule::in([0, 1]), Rule::exists(PaymentMethod::class, 'id')],
        ];
    }
}
