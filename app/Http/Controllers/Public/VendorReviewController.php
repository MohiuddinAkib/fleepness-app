<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Models\VendorReview;
use App\Models\VendorProfile;
use App\Data\VendorReviewData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Public\StoreVendorReviewData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Vendors', 'Vendor reviews exposed as a dedicated nested resource.')]
class VendorReviewController extends Controller
{
    #[Endpoint('List vendor reviews')]
    #[Response('{"data": [{"id": 1, "rating": 5, "comment": "Great vendor!"}]}', 200)]
    #[Unauthenticated]
    public function index(VendorProfile $vendorProfile): JsonResponse|Responsable
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
    public function store(
        StoreVendorReviewData $data,
        VendorProfile $vendorProfile,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($vendorProfile->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $existing = $vendorProfile->reviews()->whereBelongsTo($user)->first();

        abort_if(null !== $existing, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'You have already reviewed this vendor.');

        $review = $vendorProfile->reviews()->create([
            'user_id' => $user->getKey(),
            'rating' => $data->rating,
            'comment' => $data->comment,
        ]);

        $review->load('user');

        return VendorReviewData::fromModel($review)->additional(['message' => 'Review submitted.']);
    }

    public function show(VendorProfile $vendorProfile, VendorReview $vendorReview): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    public function update(VendorProfile $vendorProfile, VendorReview $vendorReview): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    #[Authenticated]
    #[Endpoint('Delete own vendor review')]
    #[Response('{"message": "Review deleted."}', 200)]
    public function destroy(
        VendorProfile $vendorProfile,
        VendorReview $review,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($review->user()->is($user), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($review->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_NOT_FOUND);

        $review->delete();

        return response()->json(['message' => 'Review deleted.']);
    }
}
