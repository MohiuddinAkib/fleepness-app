<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use App\Data\ProductData;
use App\Enums\ProductStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Data\Me\ListOwnProductsData;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use App\Data\Product\StoreProductData;
use App\Data\Product\UpdateProductData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Response\MessageResponseData;
use Knuckles\Scribe\Attributes\QueryParam;
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
    #[QueryParam('q', 'string', required: false, example: 'sku-001')]
    #[Response('{"data": [{"id": 1, "name": "Blue T-Shirt", "selling_price": "25.00", "status": "active"}], "meta": {"current_page": 1}}', 200)]
    /** @return PaginatedDataCollection<ProductData> */
    public function index(
        ListOwnProductsData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $products = Product::query()
            ->where('vendor_profile_id', $vendorProfile->getKey())
            ->with(['media', 'category', 'tags'])
            ->when(
                filled($data->q),
                fn ($query) => $query->where(function ($productQuery) use ($data): void {
                    $productQuery
                        ->whereLike('name', '%'.$data->q.'%')
                        ->orWhereLike('sku', '%'.$data->q.'%');
                })
            )
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
    /** @return ProductData */
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
            'status' => $data->isActive instanceof Optional
                ? ProductStatus::Inactive
                : ($data->isActive ? ProductStatus::Active : ProductStatus::Inactive),
            'is_approved' => false,
            'quantity' => $data->quantity instanceof Optional ? 0 : (int) $data->quantity,
        ]);

        $this->syncProductTags($product, $data->tags);
        $this->attachProductImages($product, $data->images);

        $product->load(['media', 'category', 'tags']);

        return ProductData::fromModel($product);
    }

    #[Authenticated]
    #[Endpoint('Get own product')]
    #[Response('{"data": {"id": 1, "name": "Blue T-Shirt"}}', 200)]
    /** @return ProductData */
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
    /** @return ProductData */
    public function update(
        UpdateProductData $data,
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $updates = array_filter([
            'name' => $data->name instanceof Optional ? null : $data->name,
            'category_id' => $data->categoryId instanceof Optional ? null : $data->categoryId,
            'size_template_id' => $data->sizeTemplateId instanceof Optional ? null : $data->sizeTemplateId,
            'sku' => $data->skuValue instanceof Optional ? null : $data->skuValue,
            'selling_price' => $data->sellingPrice instanceof Optional ? null : $data->sellingPrice,
            'discount_price' => $data->discountPrice instanceof Optional ? null : $data->discountPrice,
            'short_description' => $data->shortDescription instanceof Optional ? null : $data->shortDescription,
            'description' => $data->description instanceof Optional ? null : $data->description,
            'quantity' => $data->quantity instanceof Optional ? null : (int) $data->quantity,
            'status' => $data->isActive instanceof Optional
                ? null
                : ($data->isActive ? ProductStatus::Active : ProductStatus::Inactive),
        ], static fn (mixed $value): bool => null !== $value);

        if ([] !== $updates) {
            $product->update($updates);
        }

        if (! $data->tags instanceof Optional) {
            $this->syncProductTags($product, $data->tags);
        }

        if (! $data->images instanceof Optional) {
            $this->attachProductImages($product, $data->images);
        }

        $product->load(['media', 'category', 'tags', 'variants']);

        return ProductData::fromModel($product);
    }

    #[Authenticated]
    #[Endpoint('Delete product')]
    #[Response('{"message": "Product deleted."}', 200)]
    /** @return MessageResponseData */
    public function destroy(
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Product deleted.',
        ])->toArray());
    }

    /** @param array<int, int> $tags */
    protected function syncProductTags(Product $product, array $tags): void
    {
        $product->tags()->sync($tags);
    }

    /** @param array<int, UploadedFile> $images */
    protected function attachProductImages(Product $product, array $images): void
    {
        foreach ($images as $image) {
            $product->addMedia($image)->toMediaCollection('images');
        }
    }
}
