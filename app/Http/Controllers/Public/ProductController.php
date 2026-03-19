<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Models\Product;
use App\Data\ProductData;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Data\ProductReviewData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\QueryParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Catalog', 'Public product browsing, search, filtering and reviews.')]
class ProductController extends Controller
{
    #[Endpoint('List products', 'Browse all active approved products. Supports filtering by category, tag, vendor, price range, and full-text search.')]
    #[QueryParam('search', 'string', required: false, example: 't-shirt')]
    #[QueryParam('category_id', 'integer', required: false)]
    #[QueryParam('tag_id', 'integer', required: false)]
    #[QueryParam('vendor_id', 'integer', required: false)]
    #[QueryParam('min_price', 'number', required: false)]
    #[QueryParam('max_price', 'number', required: false)]
    #[QueryParam('per_page', 'integer', required: false, example: 15)]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt", "selling_price": "25.00"}], "meta": {"current_page": 1}}', 200)]
    #[Unauthenticated]
    public function index(Request $request): JsonResponse|Responsable
    {
        $query = Product::query()
            ->active()
            ->approved()
            ->with(['media', 'category', 'vendorProfile']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('tag_id')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $request->integer('tag_id')));
        }

        return ProductData::collect($query->paginate(), PaginatedDataCollection::class);
    }

    #[Endpoint('Get product')]
    #[Response('{"data": {"id": 1, "name": "Blue T-Shirt", "selling_price": "25.00", "vendor_profile": {}}}', 200)]
    #[Unauthenticated]
    public function show(Product $product): JsonResponse|Responsable
    {
        abort_unless($product->is_active && $product->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $product->load(['category', 'media', 'variants', 'tags', 'vendorProfile']);

        return ProductData::fromModel($product);
    }

    #[Endpoint('List product reviews')]
    #[Response('{"data": [{"id": 1, "rating": 5, "review": "Great quality!"}]}', 200)]
    #[Unauthenticated]
    public function reviews(Product $product): JsonResponse|Responsable
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
    public function storeReview(Request $request, Product $product, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($product->is_active && $product->is_approved, HttpResponse::HTTP_NOT_FOUND);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        $existing = $product->reviews()->where('user_id', $user->getKey())->first();

        abort_if(null !== $existing, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Already reviewed.');

        $review = $product->reviews()->create([
            'user_id' => $user->getKey(),
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
        ]);

        $review->load('user');

        return ProductReviewData::fromModel($review)->additional(['message' => 'Review submitted.']);
    }

    #[Authenticated]
    #[Endpoint('Delete own product review')]
    #[Response('{"message": "Review deleted."}', 200)]
    public function destroyReview(Product $product, ProductReview $review, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($review->user()->is($user), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($review->product()->is($product), HttpResponse::HTTP_NOT_FOUND);

        $review->delete();

        return response()->json(['message' => 'Review deleted.']);
    }

    #[Endpoint('Get similar products', 'Returns products from the same category or vendor.')]
    #[Response('{"data": [{"id": 2, "name": "Red T-Shirt"}]}', 200)]
    #[Unauthenticated]
    public function similar(Product $product): JsonResponse|Responsable
    {
        $similar = Product::query()
            ->active()
            ->approved()
            ->where('category_id', $product->category_id)
            ->except($product)
            ->with(['media', 'category', 'vendorProfile'])
            ->limit(10)
            ->get();

        return ProductData::collect($similar, DataCollection::class);
    }
}
