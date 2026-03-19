<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use App\Data\ProductData;
use App\Enums\ProductStatus;
use App\Attributes\CurrentUser;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Product\StoreProductData;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProductController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $products = Product::query()
            ->where('vendor_profile_id', $vendorProfile->getKey())
            ->with(['media', 'category', 'tags'])
            ->latest()
            ->paginate();

        return Response::json(ProductData::collect($products));
    }

    public function store(
        StoreProductData $data,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $product = Product::query()->create([
            'vendor_profile_id' => $vendorProfile->getKey(),
            'name' => $data->name,
            'category_id' => $data->categoryId instanceof Optional ? null : $data->categoryId,
            'size_template_id' => $data->sizeTemplateId instanceof Optional ? null : $data->sizeTemplateId,
            'sku' => $data->skuValue instanceof Optional ? null : $data->skuValue,
            'selling_price' => $data->sellingPrice instanceof Optional ? 0 : $data->sellingPrice,
            'discount_price' => $data->discountPrice instanceof Optional ? null : $data->discountPrice,
            'short_description' => $data->shortDescription instanceof Optional ? null : $data->shortDescription,
            'description' => $data->description instanceof Optional ? null : $data->description,
            'status' => ProductStatus::Inactive,
            'is_approved' => false,
            'quantity' => $data->quantity instanceof Optional ? 0 : (int) $data->quantity,
        ]);

        $product->load(['media', 'category', 'tags']);

        return Response::json(
            ['data' => ProductData::fromModel($product)],
            HttpResponse::HTTP_CREATED
        );
    }

    public function show(
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->load(['media', 'category', 'tags', 'variants']);

        return Response::json(['data' => ProductData::fromModel($product)]);
    }

    public function update(
        StoreProductData $data,
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->update(array_filter([
            'name' => $data->name,
            'category_id' => $data->categoryId instanceof Optional ? $product->category_id : $data->categoryId,
            'size_template_id' => $data->sizeTemplateId instanceof Optional ? $product->size_template_id : $data->sizeTemplateId,
            'sku' => $data->skuValue instanceof Optional ? $product->sku : $data->skuValue,
            'selling_price' => $data->sellingPrice instanceof Optional ? $product->selling_price : $data->sellingPrice,
            'discount_price' => $data->discountPrice instanceof Optional ? $product->discount_price : $data->discountPrice,
            'short_description' => $data->shortDescription instanceof Optional ? $product->short_description : $data->shortDescription,
            'description' => $data->description instanceof Optional ? $product->description : $data->description,
            'quantity' => $data->quantity instanceof Optional ? $product->quantity : (int) $data->quantity,
        ], fn ($v) => null !== $v));

        $product->load(['media', 'category', 'tags', 'variants']);

        return Response::json(['data' => ProductData::fromModel($product)]);
    }

    public function destroy(
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->delete();

        return Response::json(['message' => 'Product deleted.']);
    }

    public function toggleStatus(
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->update([
            'status' => $product->is_active
                ? ProductStatus::Inactive
                : ProductStatus::Active,
        ]);

        return Response::json(['data' => ['status' => $product->fresh()->status]]);
    }

    public function destroyImage(
        Product $product,
        int $mediaId,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $media = $product->getMedia('images')->firstWhere('id', $mediaId);
        abort_if(null === $media, HttpResponse::HTTP_NOT_FOUND);

        $media->delete();

        return Response::json(['message' => 'Image deleted.']);
    }
}
