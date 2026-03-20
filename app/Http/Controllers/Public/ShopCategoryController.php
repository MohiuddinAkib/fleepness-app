<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\ShopCategory;
use App\Data\ShopCategoryData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Discovery', 'Reference data used during checkout and storefront browsing.')]
class ShopCategoryController extends Controller
{
    #[Endpoint('List shop categories')]
    #[Response('{"data":[{"id":1,"name":"Fashion"}]}', 200)]
    #[Unauthenticated]
    /** @return DataCollection<ShopCategoryData> */
    public function index(): JsonResponse|Responsable
    {
        $categories = ShopCategory::query()->orderBy('name')->get();

        return ShopCategoryData::collect($categories, DataCollection::class);
    }
}
