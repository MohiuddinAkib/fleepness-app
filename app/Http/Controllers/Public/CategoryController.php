<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Category;
use App\Data\ProductData;
use App\Data\CategoryData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelData\PaginatedDataCollection;

class CategoryController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $categories = Category::query()
            ->active()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return CategoryData::collect($categories, DataCollection::class);
    }

    public function show(Category $category): JsonResponse|Responsable
    {
        return CategoryData::fromModel($category->load('children'));
    }

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
