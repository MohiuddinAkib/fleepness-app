<?php

declare(strict_types=1);

namespace App\Data\Product;

use App\Models\Tag;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Optional;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class UpdateProductData extends Data
{
    public function __construct(
        public readonly Optional|string $name,
        public readonly null|int|Optional $categoryId,
        public readonly null|int|Optional $sizeTemplateId,
        public readonly null|Optional|string $skuValue,
        public readonly null|float|Optional $sellingPrice,
        public readonly null|float|Optional $discountPrice,
        public readonly null|Optional|string $shortDescription,
        public readonly null|Optional|string $description,
        public readonly null|int|Optional $quantity,
        /** @var array<int, UploadedFile>|Optional */
        public readonly array|Optional $images,
        /** @var array<int, int>|Optional */
        public readonly array|Optional $tags,
        public readonly bool|Optional $isActive,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'category_id' => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'size_template_id' => ['sometimes', 'nullable', 'integer', 'exists:size_templates,id'],
            'selling_price' => ['sometimes', 'numeric', 'min:0'],
            'discount_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'quantity' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'images' => ['sometimes', 'array'],
            'images.*' => [File::image()->max(5 * 1024)],
            'tags' => ['sometimes', 'array'],
            'tags.*' => [Rule::exists(Tag::class, 'id')],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
