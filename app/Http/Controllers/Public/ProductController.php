<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Models\Product;
use App\Data\ProductData;
use App\Enums\ProductStatus;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Attributes\CurrentUser;
use App\Data\ProductReviewData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->where('status', ProductStatus::Active)
            ->where('is_approved', true)
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

        return Response::json(ProductData::collect($query->paginate()));
    }

    public function show(Product $product): JsonResponse
    {
        abort_unless(
            ProductStatus::Active === $product->status && $product->is_approved,
            HttpResponse::HTTP_NOT_FOUND
        );

        $product->load(['category', 'media', 'variants', 'tags', 'vendorProfile']);

        return Response::json([
            'data' => ProductData::fromModel($product),
        ]);
    }

    public function reviews(Product $product): JsonResponse
    {
        abort_unless(
            ProductStatus::Active === $product->status && $product->is_approved,
            HttpResponse::HTTP_NOT_FOUND
        );

        $reviews = $product->reviews()->with('user')->paginate();

        return Response::json(ProductReviewData::collect($reviews));
    }

    public function storeReview(Request $request, Product $product, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless(
            ProductStatus::Active === $product->status && $product->is_approved,
            HttpResponse::HTTP_NOT_FOUND
        );

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

        return Response::json([
            'message' => 'Review submitted.',
            'data' => ProductReviewData::fromModel($review),
        ], HttpResponse::HTTP_CREATED);
    }

    public function destroyReview(Product $product, ProductReview $review, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($review->user()->is($user), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($review->product()->is($product), HttpResponse::HTTP_NOT_FOUND);

        $review->delete();

        return Response::json(['message' => 'Review deleted.']);
    }

    public function similar(Product $product): JsonResponse
    {
        $similar = Product::query()
            ->where('status', ProductStatus::Active)
            ->where('is_approved', true)
            ->where('category_id', $product->category_id)
            ->where($product->getKeyName(), '!=', $product->getKey())
            ->with(['media', 'category', 'vendorProfile'])
            ->limit(10)
            ->get();

        return Response::json([
            'data' => ProductData::collect($similar),
        ]);
    }
}
