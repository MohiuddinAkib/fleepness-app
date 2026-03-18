<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Category;
use App\Data\CategoryData;
use App\Enums\CategoryStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->where('status', CategoryStatus::Active)
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
}
