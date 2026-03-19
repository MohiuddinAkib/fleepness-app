<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Tag;
use App\Data\TagData;
use App\Data\ProductData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelData\PaginatedDataCollection;

class TagController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $tags = Tag::query()->orderBy('name')->get();

        return TagData::collect($tags, DataCollection::class);
    }

    public function products(Tag $tag): JsonResponse|Responsable
    {
        $products = $tag->products()
            ->active()
            ->approved()
            ->with(['media', 'vendorProfile', 'category'])
            ->paginate();

        return ProductData::collect($products, PaginatedDataCollection::class);
    }
}
