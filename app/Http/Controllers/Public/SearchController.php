<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\Product;
use App\Models\VendorProfile;
use App\Data\Public\SearchData;
use App\Data\SearchResultsData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
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
    /** @return SearchResultsData */
    public function index(SearchData $data): JsonResponse|Responsable
    {
        $query = (string) ($data->q ?? '');

        if ('' === $query) {
            return SearchResultsData::fromModels(collect(), collect());
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

        return SearchResultsData::fromModels($products, $vendors);
    }
}
