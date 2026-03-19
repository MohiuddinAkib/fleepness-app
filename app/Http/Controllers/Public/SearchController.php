<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Product;
use App\Data\ProductData;
use Illuminate\Http\Request;
use App\Models\VendorProfile;
use App\Data\VendorProfileData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\QueryParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Discovery', 'Search across marketplace products and vendor profiles.')]
class SearchController extends Controller
{
    #[Endpoint('Search products and vendors')]
    #[QueryParam('q', 'string', required: true, example: 'flash sale')]
    #[Response('{"data":{"products":[{"id":15,"name":"Flash Deal Tee"}],"vendors":[{"id":4,"shop_name":"Flash Store"}]}}', 200)]
    #[Unauthenticated]
    public function index(Request $request): JsonResponse|Responsable
    {
        $query = (string) $request->string('q');

        if ('' === $query) {
            return response()->json([
                'data' => ['products' => [], 'vendors' => []],
            ]);
        }

        $products = Product::query()
            ->active()
            ->approved()
            ->whereLike('name', "%{$query}%")
            ->with(['media', 'category', 'vendorProfile'])
            ->limit(15)
            ->get();

        $vendors = VendorProfile::query()
            ->approved()
            ->whereLike('shop_name', "%{$query}%")
            ->limit(15)
            ->get();

        return response()->json([
            'data' => [
                'products' => ProductData::collect($products, DataCollection::class),
                'vendors' => VendorProfileData::collect($vendors, DataCollection::class),
            ],
        ]);
    }
}
