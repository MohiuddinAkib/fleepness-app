<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductReview;
use Illuminate\Http\UploadedFile;

it('lists active approved products', function (): void {
    Product::factory()->count(4)->create();
    Product::factory()->count(2)->inactive()->create();
    Product::factory()->count(1)->unapproved()->create();

    $response = $this->getJson('/api/v1/products');

    $response->assertOk()->assertJsonCount(4, 'data');
});

it('filters products by category', function (): void {
    $cat = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $cat->getKey()]);
    Product::factory()->count(2)->create();

    $response = $this->getJson("/api/v1/products?category_id={$cat->getKey()}");

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('searches products by name', function (): void {
    Product::factory()->create(['name' => 'blue jeans']);
    Product::factory()->create(['name' => 'red shirt']);

    $response = $this->getJson('/api/v1/products?q=blue');

    $response->assertOk()->assertJsonCount(1, 'data');
});

it('shows a single approved product', function (): void {
    $product = Product::factory()->create(['name' => 'Test Product']);
    $product
        ->addMedia(UploadedFile::fake()->image('hero-image.jpg'))
        ->toMediaCollection('images');

    $response = $this->getJson("/api/v1/products/{$product->getKey()}");

    $response->assertOk()
        ->assertJsonPath('data.name', 'Test Product')
        ->assertJsonPath('data.code', $product->sku)
        ->assertJsonPath('data.long_description', $product->description)
        ->assertJsonPath('data.order_count', 0)
        ->assertJsonPath('data.images.0.id', $product->getMedia('images')->first()?->getKey())
        ->assertJsonPath('data.images.0.path', $product->getFirstMediaUrl('images'));
});

it('returns 404 for inactive product', function (): void {
    $product = Product::factory()->inactive()->create();

    $this->getJson("/api/v1/products/{$product->getKey()}")->assertNotFound();
});

it('lists product reviews', function (): void {
    $product = Product::factory()->create();
    ProductReview::factory()->count(3)->for($product)->create();

    $response = $this->getJson("/api/v1/products/{$product->getKey()}/reviews");

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('submits a product review', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson("/api/v1/products/{$product->getKey()}/reviews", [
        'rating' => 5,
        'review' => 'Excellent!',
    ]);

    $response->assertCreated()->assertJsonPath('data.rating', 5);
    expect(ProductReview::where('product_id', $product->getKey())->count())->toBe(1);
});

it('cannot review the same product twice', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    ProductReview::factory()->create([
        'user_id' => $user->getKey(),
        'product_id' => $product->getKey(),
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson("/api/v1/products/{$product->getKey()}/reviews", [
        'rating' => 3,
    ])->assertUnprocessable();
});

it('deletes own product review', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $review = ProductReview::factory()->create([
        'user_id' => $user->getKey(),
        'product_id' => $product->getKey(),
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/v1/products/{$product->getKey()}/reviews/{$review->getKey()}")
        ->assertOk();

    expect(ProductReview::find($review->getKey()))->toBeNull();
});

it('cannot delete another user\'s review', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $review = ProductReview::factory()->create(['product_id' => $product->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/v1/products/{$product->getKey()}/reviews/{$review->getKey()}")
        ->assertForbidden();
});
