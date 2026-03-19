<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Product;
use Spatie\LaravelData\Data;
use App\Models\VendorProfile;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class SearchResultsData extends Data
{
    /**
     * @param  list<array<string, mixed>>  $products
     * @param  list<array<string, mixed>>  $vendors
     */
    public function __construct(
        public readonly array $products,
        public readonly array $vendors,
    ) {}

    /**
     * @param  Collection<int, Product>  $products
     * @param  Collection<int, VendorProfile>  $vendors
     */
    public static function fromModels(Collection $products, Collection $vendors): self
    {
        return new self(
            products: $products->map(fn (Product $product) => ProductData::fromModel($product)->toArray())->values()->all(),
            vendors: $vendors->map(fn (VendorProfile $vendorProfile) => VendorProfileData::fromModel($vendorProfile)->toArray())->values()->all(),
        );
    }
}
