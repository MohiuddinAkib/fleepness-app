<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Product;
use App\Enums\ProductStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class ProductData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $sku,
        public readonly ?string $code,
        public readonly int $quantity,
        public readonly int $orderCount,
        public readonly string $sellingPrice,
        public readonly ?string $discountPrice,
        public readonly ?string $shortDescription,
        public readonly ?string $description,
        public readonly ?string $longDescription,
        public readonly ProductStatus $status,
        public readonly bool $isApproved,
        public readonly ?VendorProfileData $vendor,
        public readonly ?CategoryData $category,
        /** @var list<TagData> */
        public readonly array $tags,
        /** @var list<array{id: int, path: string, alt_text: ?string}> */
        public readonly array $images,
        /** @var list<array{id: int, name: string, price: string, stock: int}> */
        public readonly array $variants,
    ) {}

    public static function fromModel(Product $product): self
    {
        return new self(
            id: (int) $product->getKey(),
            name: $product->name,
            slug: $product->slug,
            sku: $product->sku,
            code: $product->sku,
            quantity: (int) $product->quantity,
            orderCount: (int) $product->order_count,
            sellingPrice: (string) $product->selling_price,
            discountPrice: $product->discount_price ? (string) $product->discount_price : null,
            shortDescription: $product->short_description,
            description: $product->description,
            longDescription: $product->description,
            status: $product->status,
            isApproved: (bool) $product->is_approved,
            vendor: $product->relationLoaded('vendorProfile')
                ? VendorProfileData::fromModel($product->vendorProfile)
                : null,
            category: $product->relationLoaded('category') && null !== $product->category
                ? CategoryData::fromModel($product->category)
                : null,
            tags: $product->relationLoaded('tags')
                ? $product->tags->map(fn ($tag) => TagData::fromModel($tag))->values()->all()
                : [],
            images: $product->getMedia('images')->map(fn ($media) => ProductImageData::fromMedia($media)->toArray())->values()->all(),
            variants: $product->relationLoaded('variants')
                ? $product->variants->map(fn ($v) => [
                    'id' => (int) $v->getKey(),
                    'name' => $v->name,
                    'price' => (string) $v->price,
                    'stock' => (int) $v->stock,
                ])->values()->all()
                : [],
        );
    }
}
