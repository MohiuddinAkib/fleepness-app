<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Category;
use App\Data\ProductData;
use App\Data\CategoryData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;

#[Group('Catalog')]
class CategoryController extends Controller
{
    #[Endpoint('List categories', 'Returns the full category tree.')]
    #[Response('{"data": [{"id": 1, "name": "Clothing", "children": []}]}', 200)]
    #[Unauthenticated]
    /** @return DataCollection<CategoryData> */
    public function index(): JsonResponse|Responsable
    {
        $categories = Category::query()
            ->active()
            ->whereNull('parent_id')
            ->with('children.children')
            ->orderBy('sort_order')
            ->get();

        return CategoryData::collect($categories, DataCollection::class);
    }

    #[Endpoint('Get category')]
    #[Response('{"data": {"id": 1, "name": "Clothing"}}', 200)]
    #[Unauthenticated]
    /** @return CategoryData */
    public function show(Category $category): JsonResponse|Responsable
    {
        return CategoryData::fromModel($category->load(['parent', 'children.children']));
    }

    #[Endpoint('List products in category')]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
    /** @return PaginatedDataCollection<ProductData> */
    public function products(Category $category): JsonResponse|Responsable
    {
        $products = $category->products()
            ->active()
            ->approved()
            ->with(['media', 'vendorProfile'])
            ->paginate();

        return ProductData::collect($products, PaginatedDataCollection::class);
    }
}
