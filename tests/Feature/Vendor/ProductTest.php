<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\User;
use App\Models\Product;
use App\Enums\ProductStatus;
use App\Models\VendorProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('requires vendor profile to list products', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/me/products')->assertForbidden();
});

it('lists vendor own products', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    Product::factory()->count(3)->for($vendor, 'vendorProfile')->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/v1/me/products');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('filters vendor own products by search query', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    Product::factory()->for($vendor, 'vendorProfile')->create(['name' => 'Flash Deal Tee', 'sku' => 'FLASH-001']);
    Product::factory()->for($vendor, 'vendorProfile')->create(['name' => 'Winter Jacket', 'sku' => 'JACKET-001']);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/v1/me/products?q=flash');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Flash Deal Tee');
});

it('creates a product', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $tag = Tag::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->post('/api/v1/me/products', [
        'name' => 'Cool T-Shirt',
        'quantity' => 50,
        'selling_price' => 500,
        'tags' => [$tag->getKey()],
        'images' => [
            UploadedFile::fake()->image('product.jpg'),
        ],
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Cool T-Shirt')
        ->assertJsonPath('data.is_approved', false)
        ->assertJsonCount(1, 'data.images')
        ->assertJsonCount(1, 'data.tags');

    $product = Product::query()->where('name', 'Cool T-Shirt')->firstOrFail();

    expect($product->getMedia('images'))->toHaveCount(1)
        ->and($product->tags()->whereKey($tag->getKey())->exists())->toBeTrue();
});

it('returns 422 for missing required fields', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/v1/me/products', [])->assertUnprocessable();
});

it('shows own product', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create(['name' => 'My Product']);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson("/api/v1/me/products/{$product->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.name', 'My Product');
});

it('cannot see another vendor\'s product', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $otherProduct = Product::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson("/api/v1/me/products/{$otherProduct->getKey()}")
        ->assertForbidden();
});

it('updates a product', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create();
    $existingTag = Tag::factory()->create();
    $replacementTag = Tag::factory()->create();
    $product->tags()->sync([$existingTag->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patch("/api/v1/me/products/{$product->getKey()}", [
        'name' => 'Updated Name',
        'quantity' => 99,
        'is_active' => true,
        'tags' => [$replacementTag->getKey()],
        'images' => [
            UploadedFile::fake()->image('updated.jpg'),
        ],
    ])->assertOk()
        ->assertJsonPath('data.name', 'Updated Name')
        ->assertJsonCount(1, 'data.images')
        ->assertJsonCount(1, 'data.tags');

    expect($product->fresh()->quantity)->toBe(99)
        ->and($product->fresh()->status)->toBe(ProductStatus::Active)
        ->and($product->fresh()->getMedia('images'))->toHaveCount(1)
        ->and($product->fresh()->tags()->whereKey($replacementTag->getKey())->exists())->toBeTrue();
});

it('deletes a product', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->deleteJson("/api/v1/me/products/{$product->getKey()}")->assertOk();

    expect(Product::withTrashed()->find($product->getKey())->trashed())->toBeTrue();
});

it('toggles product status', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create(['status' => ProductStatus::Active]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patchJson("/api/v1/me/products/{$product->getKey()}/status")
        ->assertOk();

    expect($product->fresh()->status)->toBe(ProductStatus::Inactive);
});
