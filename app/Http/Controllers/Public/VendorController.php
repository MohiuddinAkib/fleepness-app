<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\ProductData;
use App\Data\ShortVideoData;
use App\Models\VendorProfile;
use App\Data\VendorProfileData;
use Illuminate\Http\JsonResponse;
use App\Data\Public\ListVendorsData;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Data\Public\ListVendorProductsData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Vendors', 'Browse vendor profiles, their products, short videos and reviews. Follow/unfollow vendors.')]
class VendorController extends Controller
{
    #[Endpoint('List vendors')]
    #[QueryParam('search', 'string', required: false)]
    #[QueryParam('shop_category_id', 'integer', required: false)]
    #[QueryParam('similar_to_vendor_id', 'integer', required: false)]
    #[Response('{"data": [{"id": 1, "shop_name": "Flash Store", "status": "approved"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
    public function index(ListVendorsData $data): JsonResponse|Responsable
    {
        $vendors = VendorProfile::query()
            ->approved()
            ->when(
                filled($data->search),
                fn ($query) => $query->whereLike('shop_name', '%'.$data->search.'%')
            )
            ->when(
                null !== $data->shopCategoryId,
                fn ($query) => $query->where('shop_category_id', $data->shopCategoryId)
            )
            ->when(
                null !== $data->similarToVendorId,
                function ($query) use ($data): void {
                    $sourceVendor = VendorProfile::query()->find($data->similarToVendorId);

                    if (! $sourceVendor instanceof VendorProfile) {
                        $query->whereRaw('1 = 0');

                        return;
                    }

                    $query->where('shop_category_id', $sourceVendor->shop_category_id)
                        ->whereKeyNot($sourceVendor->getKey());
                }
            )
            ->paginate();

        return VendorProfileData::collect($vendors, PaginatedDataCollection::class);
    }

    #[Endpoint('Get vendor profile')]
    #[Response('{"data": {"id": 1, "shop_name": "Flash Store", "description": "Best deals", "order_count": 120}}', 200)]
    #[Unauthenticated]
    public function show(VendorProfile $vendorProfile): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        return VendorProfileData::fromModel($vendorProfile);
    }

    #[Authenticated]
    #[Endpoint('Follow vendor')]
    #[Response('{"message": "Following."}', 200)]
    public function follow(VendorProfile $vendorProfile, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $user->following()->syncWithoutDetaching([$vendorProfile->getKey()]);

        return response()->json(['message' => 'Vendor followed.']);
    }

    #[Authenticated]
    #[Endpoint('Unfollow vendor')]
    #[Response('{"message": "Unfollowed."}', 200)]
    public function unfollow(VendorProfile $vendorProfile, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $user->following()->detach($vendorProfile->getKey());

        return response()->json(['message' => 'Vendor unfollowed.']);
    }

    #[Endpoint('List vendor products')]
    #[QueryParam('q', 'string', required: false)]
    #[QueryParam('min_price', 'number', required: false)]
    #[QueryParam('max_price', 'number', required: false)]
    #[QueryParam('price_category', 'string', required: false, example: 'low')]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
    public function products(
        VendorProfile $vendorProfile,
        ListVendorProductsData $data,
    ): JsonResponse|Responsable {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        [$minPrice, $maxPrice] = $this->resolvePriceRange($data);

        $products = $vendorProfile->products()
            ->active()
            ->approved()
            ->with(['media', 'category'])
            ->when(
                filled($data->q),
                fn ($query) => $query->whereLike('name', '%'.$data->q.'%')
            )
            ->when(
                null !== $minPrice || null !== $maxPrice,
                function ($query) use ($minPrice, $maxPrice): void {
                    $query->whereRaw(
                        'CAST(COALESCE(discount_price, selling_price) AS REAL) >= ?',
                        [$minPrice ?? 0]
                    );

                    if (null !== $maxPrice) {
                        $query->whereRaw(
                            'CAST(COALESCE(discount_price, selling_price) AS REAL) <= ?',
                            [$maxPrice]
                        );
                    }
                }
            )
            ->paginate();

        return ProductData::collect($products, PaginatedDataCollection::class);
    }

    #[Endpoint('List vendor short videos')]
    #[Response('{"data": [{"id": 1, "title": "New Collection Drop"}]}', 200)]
    #[Unauthenticated]
    public function shortVideos(VendorProfile $vendorProfile): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $videos = $vendorProfile->shortVideos()
            ->with(['media'])
            ->latest()
            ->paginate();

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }

    /**
     * @return array{0: float|null, 1: float|null}
     */
    private function resolvePriceRange(ListVendorProductsData $data): array
    {
        if (null !== $data->priceCategory) {
            return match ($data->priceCategory) {
                'low' => [1.0, 500.0],
                'medium' => [501.0, 1000.0],
                'premium' => [1001.0, null],
                default => [null, null],
            };
        }

        return [$data->minPrice, $data->maxPrice];
    }
}
