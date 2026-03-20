<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Product;
use App\Data\ProductData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Public\ListProductsData;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\QueryParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Catalog', 'Public product browsing, search, filtering and reviews.')]
class ProductController extends Controller
{
    #[Endpoint('List products', 'Browse all active approved products. Supports filtering by category, tag, vendor, price range, and full-text search.')]
    #[QueryParam('q', 'string', required: false, example: 't-shirt')]
    #[QueryParam('category_id', 'integer', required: false)]
    #[QueryParam('tag_id', 'integer', required: false)]
    #[QueryParam('vendor_id', 'integer', required: false)]
    #[QueryParam('min_price', 'number', required: false)]
    #[QueryParam('max_price', 'number', required: false)]
    #[QueryParam('per_page', 'integer', required: false, example: 15)]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt", "selling_price": "25.00"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
    /** @return PaginatedDataCollection<ProductData> */
    public function index(ListProductsData $data): JsonResponse|Responsable
    {
        $query = Product::query()
            ->active()
            ->approved()
            ->with(['media', 'category', 'vendorProfile']);

        if (null !== $data->categoryId) {
            $query->where('category_id', $data->categoryId);
        }

        if (filled($data->q)) {
            $query->whereLike('name', "%{$data->q}%");
        }

        if (null !== $data->tagId) {
            $query->whereHas('tags', fn ($query) => $query->where('tags.id', $data->tagId));
        }

        if (null !== $data->vendorId) {
            $query->where('vendor_profile_id', $data->vendorId);
        }

        if (null !== $data->minPrice || null !== $data->maxPrice) {
            $query->whereRaw(
                'CAST(COALESCE(discount_price, selling_price) AS REAL) >= ?',
                [$data->minPrice ?? 0]
            );

            if (null !== $data->maxPrice) {
                $query->whereRaw(
                    'CAST(COALESCE(discount_price, selling_price) AS REAL) <= ?',
                    [$data->maxPrice]
                );
            }
        }

        return ProductData::collect(
            $query->paginate(perPage: $data->perPage, page: $data->page),
            PaginatedDataCollection::class
        );
    }

    #[Endpoint('Get product')]
    #[Response('{"data": {"id": 1, "name": "Blue T-Shirt", "selling_price": "25.00", "vendor_profile": {}}}', 200)]
    #[Unauthenticated]
    /** @return ProductData */
    public function show(Product $product): JsonResponse|Responsable
    {
        abort_unless($product->is_active && $product->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $product->load(['category', 'media', 'variants', 'tags', 'vendorProfile']);

        return ProductData::fromModel($product);
    }

    #[Endpoint('Get similar products', 'Returns products from the same category or vendor.')]
    #[Response('{"data": [{"id": 2, "name": "Red T-Shirt"}]}', 200)]
    #[Unauthenticated]
    /** @return DataCollection<ProductData> */
    public function similar(Product $product): JsonResponse|Responsable
    {
        $similar = Product::query()
            ->active()
            ->approved()
            ->where('category_id', $product->category_id)
            ->except($product)
            ->with(['media', 'category', 'vendorProfile'])
            ->limit(10)
            ->get();

        return ProductData::collect($similar, DataCollection::class);
    }
}
