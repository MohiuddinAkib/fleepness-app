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
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;

class SearchController extends Controller
{
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
            ->where('name', 'like', "%{$query}%")
            ->with(['media', 'category', 'vendorProfile'])
            ->limit(15)
            ->get();

        $vendors = VendorProfile::query()
            ->approved()
            ->where('shop_name', 'like', "%{$query}%")
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
