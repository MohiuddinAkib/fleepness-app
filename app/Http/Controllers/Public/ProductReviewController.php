<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductReview;
use App\Data\ProductReviewData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Response\MessageResponseData;
use App\Data\Public\StoreProductReviewData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Catalog', 'Public product reviews exposed as a dedicated nested resource.')]
class ProductReviewController extends Controller
{
    #[Endpoint('List product reviews')]
    #[Response('{"data": [{"id": 1, "rating": 5, "review": "Great quality!"}]}', 200)]
    #[Unauthenticated]
    /** @return PaginatedDataCollection<ProductReviewData> */
    public function index(Product $product): JsonResponse|Responsable
    {
        abort_unless($product->is_active && $product->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $reviews = $product->reviews()->with('user')->paginate();

        return ProductReviewData::collect($reviews, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('rating', 'integer', required: true, example: 5)]
    #[BodyParam('review', 'string', required: false, example: 'Great quality!')]
    #[Endpoint('Write a product review')]
    #[Response('{"data": {"id": 1, "rating": 5}}', 201)]
    /** @return ProductReviewData */
    public function store(
        StoreProductReviewData $data,
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($product->is_active && $product->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $existing = $product->reviews()->whereBelongsTo($user)->first();

        abort_if(null !== $existing, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Already reviewed.');

        $review = $product->reviews()->create([
            'user_id' => $user->getKey(),
            'rating' => $data->rating,
            'review' => $data->review,
        ]);

        $review->load('user');

        return ProductReviewData::fromModel($review)->additional(['message' => 'Review submitted.']);
    }

    public function show(Product $product, ProductReview $productReview): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    public function update(Product $product, ProductReview $productReview): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    #[Authenticated]
    #[Endpoint('Delete own product review')]
    #[Response('{"message": "Review deleted."}', 200)]
    /** @return MessageResponseData */
    public function destroy(
        Product $product,
        ProductReview $review,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($review->user()->is($user), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($review->product()->is($product), HttpResponse::HTTP_NOT_FOUND);

        $review->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Review deleted.',
        ])->toArray());
    }
}
