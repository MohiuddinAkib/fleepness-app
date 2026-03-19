<?php

declare(strict_types=1);

namespace App\Http\Controllers\Vendor;

use App\Models\User;
use App\Models\Product;
use App\Data\ProductData;
use App\Enums\ProductStatus;
use App\Attributes\CurrentUser;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Vendor\StoreProductData;
use App\Data\Vendor\UpdateProductData;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProductController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');

        $products = $vendorProfile->products()
            ->with(['category', 'media', 'variants', 'tags'])
            ->paginate();

        return Response::json(ProductData::collect($products));
    }

    public function store(StoreProductData $data, #[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');

        $product = $vendorProfile->products()->create([
            'name' => $data->name,
            'quantity' => $data->quantity,
            'selling_price' => $data->sellingPrice,
            'discount_price' => $data->discountPrice,
            'short_description' => $data->shortDescription,
            'description' => $data->description,
            'category_id' => $data->categoryId,
            'size_template_id' => $data->sizeTemplateId,
            'status' => ProductStatus::Active,
            'is_approved' => false,
        ]);

        $product->load(['category', 'media', 'variants', 'tags']);

        return Response::json([
            'message' => 'Product created.',
            'data' => ProductData::fromModel($product),
        ], HttpResponse::HTTP_CREATED);
    }

    public function show(Product $product, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($product->vendorProfile()->is($user->vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->load(['category', 'media', 'variants', 'tags', 'vendorProfile']);

        return Response::json([
            'data' => ProductData::fromModel($product),
        ]);
    }

    public function update(UpdateProductData $data, Product $product, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($product->vendorProfile()->is($user->vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $updates = [];

        if (! $data->name instanceof Optional) {
            $updates['name'] = $data->name;
        }

        if (! $data->quantity instanceof Optional) {
            $updates['quantity'] = $data->quantity;
        }

        if (! $data->sellingPrice instanceof Optional) {
            $updates['selling_price'] = $data->sellingPrice;
        }

        if (! $data->discountPrice instanceof Optional) {
            $updates['discount_price'] = $data->discountPrice;
        }

        if (! $data->shortDescription instanceof Optional) {
            $updates['short_description'] = $data->shortDescription;
        }

        if (! $data->description instanceof Optional) {
            $updates['description'] = $data->description;
        }

        if (! $data->categoryId instanceof Optional) {
            $updates['category_id'] = $data->categoryId;
        }

        if ([] !== $updates) {
            $product->update($updates);
        }

        $product->load(['category', 'media', 'variants', 'tags']);

        return Response::json([
            'message' => 'Product updated.',
            'data' => ProductData::fromModel($product->fresh()),
        ]);
    }

    public function destroy(Product $product, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($product->vendorProfile()->is($user->vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->delete();

        return Response::json(['message' => 'Product deleted.']);
    }

    public function toggleStatus(Product $product, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($product->vendorProfile()->is($user->vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->update([
            'status' => $product->is_active
                ? ProductStatus::Inactive
                : ProductStatus::Active,
        ]);

        return Response::json([
            'message' => 'Product status updated.',
            'data' => ['status' => $product->fresh()->status],
        ]);
    }
}
