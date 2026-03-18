<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Product;
use App\Enums\ProductStatus;
use App\Models\VendorProfile;

it('requires vendor profile to list products', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/vendor/products')->assertForbidden();
});

it('lists vendor own products', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    Product::factory()->count(3)->for($vendor, 'vendorProfile')->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/vendor/products');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('creates a product', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/vendor/products', [
        'name' => 'Cool T-Shirt',
        'quantity' => 50,
        'selling_price' => 500,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Cool T-Shirt')
        ->assertJsonPath('data.is_approved', false);

    expect(Product::where('name', 'Cool T-Shirt')->count())->toBe(1);
});

it('returns 422 for missing required fields', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/vendor/products', [])->assertUnprocessable();
});

it('shows own product', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create(['name' => 'My Product']);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson("/api/vendor/products/{$product->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.name', 'My Product');
});

it('cannot see another vendor\'s product', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $otherProduct = Product::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson("/api/vendor/products/{$otherProduct->getKey()}")
        ->assertForbidden();
});

it('updates a product', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patchJson("/api/vendor/products/{$product->getKey()}", [
        'name' => 'Updated Name',
        'quantity' => 99,
    ])->assertOk()->assertJsonPath('data.name', 'Updated Name');

    expect($product->fresh()->quantity)->toBe(99);
});

it('deletes a product', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->deleteJson("/api/vendor/products/{$product->getKey()}")->assertOk();

    expect(Product::withTrashed()->find($product->getKey())->trashed())->toBeTrue();
});

it('toggles product status', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create(['status' => ProductStatus::Active]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson("/api/vendor/products/{$product->getKey()}/toggle-status")
        ->assertOk();

    expect($product->fresh()->status)->toBe(ProductStatus::Inactive);
});
