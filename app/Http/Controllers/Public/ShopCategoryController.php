<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\ShopCategory;
use App\Data\ShopCategoryData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;

class ShopCategoryController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $categories = ShopCategory::query()->orderBy('name')->get();

        return ShopCategoryData::collect($categories, DataCollection::class);
    }
}
