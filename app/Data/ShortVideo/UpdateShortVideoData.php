<?php

declare(strict_types=1);

namespace App\Data\ShortVideo;

use App\Models\Product;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Optional;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\Validation\Min;

#[MapInputName(SnakeCaseMapper::class)]
class UpdateShortVideoData extends Data
{
    public function __construct(
        #[Min(3)]
        public readonly Optional|string $title,
        public readonly null|Optional|string $description,
        public readonly Optional|UploadedFile $video,
        public readonly null|Optional|UploadedFile $thumbnail,
        /** @var array<int, int>|Optional */
        public readonly array|Optional $productIds,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'min:3'],
            'description' => ['sometimes', 'nullable', 'string'],
            'video' => [
                'sometimes',
                File::types(['mp4', 'mov', 'avi', 'webm'])->max(10 * 1024),
            ],
            'thumbnail' => [
                'sometimes',
                'nullable',
                File::image()->max(5 * 1024),
            ],
            'product_ids' => ['sometimes', 'array'],
            'product_ids.*' => [Rule::exists(Product::class, 'id')],
        ];
    }
}
