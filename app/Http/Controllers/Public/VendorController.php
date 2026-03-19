<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\ProductData;
use App\Enums\VendorStatus;
use App\Data\ShortVideoData;
use App\Enums\ProductStatus;
use App\Models\VendorReview;
use App\Models\VendorProfile;
use App\Data\VendorReviewData;
use App\Attributes\CurrentUser;
use App\Data\VendorProfileData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Data\Public\StoreVendorReviewData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class VendorController extends Controller
{
    public function index(): JsonResponse
    {
        $vendors = VendorProfile::query()
            ->where('status', VendorStatus::Approved)
            ->paginate();

        return Response::json(VendorProfileData::collect($vendors));
    }

    public function show(VendorProfile $vendorProfile): JsonResponse
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        return Response::json([
            'data' => VendorProfileData::fromModel($vendorProfile),
        ]);
    }

    public function follow(VendorProfile $vendorProfile, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $user->following()->syncWithoutDetaching([$vendorProfile->getKey()]);

        return Response::json(['message' => 'Vendor followed.']);
    }

    public function unfollow(VendorProfile $vendorProfile, #[CurrentUser] User $user): JsonResponse
    {
        $user->following()->detach($vendorProfile->getKey());

        return Response::json(['message' => 'Vendor unfollowed.']);
    }

    public function reviews(VendorProfile $vendorProfile): JsonResponse
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $reviews = $vendorProfile->reviews()->with('user')->paginate();

        return Response::json(VendorReviewData::collect($reviews));
    }

    public function storeReview(
        StoreVendorReviewData $data,
        VendorProfile $vendorProfile,
        #[CurrentUser] User $user,
    ): JsonResponse {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $existing = $vendorProfile->reviews()->where('user_id', $user->getKey())->first();

        abort_if(null !== $existing, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'You have already reviewed this vendor.');

        $review = $vendorProfile->reviews()->create([
            'user_id' => $user->getKey(),
            'rating' => $data->rating,
            'comment' => $data->comment,
        ]);

        $review->load('user');

        return Response::json([
            'message' => 'Review submitted.',
            'data' => VendorReviewData::fromModel($review),
        ], HttpResponse::HTTP_CREATED);
    }

    public function destroyReview(
        VendorProfile $vendorProfile,
        VendorReview $review,
        #[CurrentUser] User $user,
    ): JsonResponse {
        abort_unless($review->user()->is($user), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($review->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_NOT_FOUND);

        $review->delete();

        return Response::json(['message' => 'Review deleted.']);
    }

    public function products(VendorProfile $vendorProfile): JsonResponse
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $products = $vendorProfile->products()
            ->where('status', ProductStatus::Active)
            ->where('is_approved', true)
            ->with(['media', 'category'])
            ->paginate();

        return Response::json(ProductData::collect($products));
    }

    public function shortVideos(VendorProfile $vendorProfile): JsonResponse
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $videos = $vendorProfile->shortVideos()
            ->with(['media'])
            ->latest()
            ->paginate();

        return Response::json(ShortVideoData::collect($videos));
    }
}
