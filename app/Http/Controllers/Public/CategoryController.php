<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Category;
use App\Data\ProductData;
use App\Data\CategoryData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->active()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return Response::json([
            'data' => CategoryData::collect($categories),
        ]);
    }

    public function show(Category $category): JsonResponse
    {
        return Response::json([
            'data' => CategoryData::fromModel($category->load('children')),
        ]);
    }

    public function products(Category $category): JsonResponse
    {
        $products = $category->products()
            ->active()
            ->approved()
            ->with(['media', 'vendorProfile'])
            ->paginate();

        return Response::json(ProductData::collect($products));
    }
}
