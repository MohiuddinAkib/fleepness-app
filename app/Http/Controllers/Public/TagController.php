<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Tag;
use App\Data\TagData;
use App\Data\ProductData;
use App\Enums\ProductStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tags = Tag::query()->orderBy('name')->get();

        return Response::json([
            'data' => TagData::collect($tags),
        ]);
    }

    public function products(Tag $tag): JsonResponse
    {
        $products = $tag->products()
            ->where('status', ProductStatus::Active)
            ->where('is_approved', true)
            ->with(['media', 'vendorProfile', 'category'])
            ->paginate();

        return Response::json(ProductData::collect($products));
    }
}
