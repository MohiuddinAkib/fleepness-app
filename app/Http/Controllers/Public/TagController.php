<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Tag;
use App\Data\TagData;
use App\Data\ProductData;
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
class TagController extends Controller
{
    #[Endpoint('List all tags')]
    #[Response('{"data": [{"id": 1, "name": "sale", "slug": "sale"}]}', 200)]
    #[Unauthenticated]
    public function index(): JsonResponse|Responsable
    {
        $tags = Tag::query()->orderBy('name')->get();

        return TagData::collect($tags, DataCollection::class);
    }

    #[Endpoint('Get tag')]
    #[Response('{"data": {"id": 1, "name": "sale", "slug": "sale"}}', 200)]
    #[Unauthenticated]
    public function show(Tag $tag): JsonResponse|Responsable
    {
        return TagData::fromModel($tag);
    }

    #[Endpoint('List products by tag')]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
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
