<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\ShopCategory;
use App\Data\ShopCategoryData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ShopCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = ShopCategory::query()->orderBy('name')->get();

        return Response::json([
            'data' => ShopCategoryData::collect($categories),
        ]);
    }
}
