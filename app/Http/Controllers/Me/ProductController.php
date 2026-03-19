<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use App\Data\ProductData;
use App\Enums\ProductStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use App\Data\Product\StoreProductData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Products', 'Vendor product management. Requires an approved vendor profile.')]
class ProductController extends Controller
{
    #[Authenticated]
    #[Endpoint('List own products', 'Returns a paginated list of products belonging to the authenticated vendor.')]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt", "selling_price": "25.00", "status": "active"}], "meta": {"current_page": 1}}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $products = Product::query()
            ->where('vendor_profile_id', $vendorProfile->getKey())
            ->with(['media', 'category', 'tags'])
            ->latest()
            ->paginate();

        return ProductData::collect($products, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('name', 'string', required: true, example: 'Blue T-Shirt')]
    #[BodyParam('category_id', 'integer', required: false)]
    #[BodyParam('selling_price', 'number', required: true, example: 25.00)]
    #[BodyParam('discount_price', 'number', required: false)]
    #[BodyParam('quantity', 'integer', required: true, example: 100)]
    #[BodyParam('description', 'string', required: false)]
    #[BodyParam('short_description', 'string', required: false)]
    #[BodyParam('sku', 'string', required: false, example: 'SKU-001')]
    #[BodyParam('size_template_id', 'integer', required: false)]
    #[Endpoint('Create product')]
    #[Response('{"data": {"id": 1, "name": "Blue T-Shirt", "status": "active"}}', 201)]
    public function store(
        StoreProductData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
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

        return ProductData::fromModel($product);
    }

    #[Authenticated]
    #[Endpoint('Get own product')]
    #[Response('{"data": {"id": 1, "name": "Blue T-Shirt"}}', 200)]
    public function show(
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->load(['media', 'category', 'tags', 'variants']);

        return ProductData::fromModel($product);
    }

    #[Authenticated]
    #[BodyParam('name', 'string', required: false)]
    #[BodyParam('category_id', 'integer', required: false)]
    #[BodyParam('selling_price', 'number', required: false)]
    #[BodyParam('discount_price', 'number', required: false)]
    #[BodyParam('quantity', 'integer', required: false)]
    #[BodyParam('description', 'string', required: false)]
    #[BodyParam('short_description', 'string', required: false)]
    #[BodyParam('sku', 'string', required: false)]
    #[BodyParam('size_template_id', 'integer', required: false)]
    #[Endpoint('Update product')]
    #[Response('{"data": {"id": 1, "name": "Updated T-Shirt"}}', 200)]
    public function update(
        StoreProductData $data,
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
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

        return ProductData::fromModel($product);
    }

    #[Authenticated]
    #[Endpoint('Delete product')]
    #[Response('{"message": "Product deleted."}', 200)]
    public function destroy(
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }

    #[Authenticated]
    #[Endpoint('Delete product image')]
    #[Response('{"message": "Image deleted."}', 200)]
    public function destroyImage(
        Product $product,
        int $mediaId,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $media = $product->getMedia('images')->firstWhere('id', $mediaId);
        abort_if(null === $media, HttpResponse::HTTP_NOT_FOUND);

        $media->delete();

        return response()->json(['message' => 'Image deleted.']);
    }
}
