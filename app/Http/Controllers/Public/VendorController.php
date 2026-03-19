<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\ProductData;
use App\Data\ShortVideoData;
use App\Models\VendorReview;
use App\Models\VendorProfile;
use App\Data\VendorReviewData;
use App\Data\VendorProfileData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Public\StoreVendorReviewData;
use Knuckles\Scribe\Attributes\QueryParam;
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
    #[Response('{"data": [{"id": 1, "shop_name": "Flash Store", "status": "approved"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
    public function index(): JsonResponse|Responsable
    {
        $vendors = VendorProfile::query()
            ->approved()
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

    #[Endpoint('List vendor reviews')]
    #[Response('{"data": [{"id": 1, "rating": 5, "comment": "Great vendor!"}]}', 200)]
    #[Unauthenticated]
    public function reviews(VendorProfile $vendorProfile): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $reviews = $vendorProfile->reviews()->with('user')->paginate();

        return VendorReviewData::collect($reviews, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('rating', 'integer', required: true, example: 4)]
    #[BodyParam('comment', 'string', required: false)]
    #[Endpoint('Write vendor review')]
    #[Response('{"data": {"id": 1, "rating": 4}}', 201)]
    public function storeReview(
        StoreVendorReviewData $data,
        VendorProfile $vendorProfile,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $existing = $vendorProfile->reviews()->where('user_id', $user->getKey())->first();

        abort_if(null !== $existing, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'You have already reviewed this vendor.');

        $review = $vendorProfile->reviews()->create([
            'user_id' => $user->getKey(),
            'rating' => $data->rating,
            'comment' => $data->comment,
        ]);

        $review->load('user');

        return VendorReviewData::fromModel($review)->additional(['message' => 'Review submitted.']);
    }

    #[Authenticated]
    #[Endpoint('Delete own vendor review')]
    #[Response('{"message": "Review deleted."}', 200)]
    public function destroyReview(
        VendorProfile $vendorProfile,
        VendorReview $review,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($review->user()->is($user), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($review->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_NOT_FOUND);

        $review->delete();

        return response()->json(['message' => 'Review deleted.']);
    }

    #[Endpoint('List vendor products')]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
    public function products(VendorProfile $vendorProfile): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $products = $vendorProfile->products()
            ->active()
            ->approved()
            ->with(['media', 'category'])
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
}
