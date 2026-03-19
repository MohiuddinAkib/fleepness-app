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
use App\Data\Public\StoreVendorReviewData;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class VendorController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $vendors = VendorProfile::query()
            ->approved()
            ->paginate();

        return VendorProfileData::collect($vendors, PaginatedDataCollection::class);
    }

    public function show(VendorProfile $vendorProfile): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        return VendorProfileData::fromModel($vendorProfile);
    }

    public function follow(VendorProfile $vendorProfile, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $user->following()->syncWithoutDetaching([$vendorProfile->getKey()]);

        return response()->json(['message' => 'Vendor followed.']);
    }

    public function unfollow(VendorProfile $vendorProfile, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $user->following()->detach($vendorProfile->getKey());

        return response()->json(['message' => 'Vendor unfollowed.']);
    }

    public function reviews(VendorProfile $vendorProfile): JsonResponse|Responsable
    {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $reviews = $vendorProfile->reviews()->with('user')->paginate();

        return VendorReviewData::collect($reviews, PaginatedDataCollection::class);
    }

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
