<?php

declare(strict_types=1);

use App\Models\Fee;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\DeliveryOption;

it('requires auth to access cart', function (): void {
    $this->getJson('/api/v1/cart')->assertUnauthorized();
});

it('lists cart items', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    CartItem::factory()->count(3)->create(['user_id' => $user->getKey()]);

    $this->withToken($token)->getJson('/api/v1/cart')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('adds item to cart', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/v1/cart/items', [
        'product_id' => $product->getKey(),
        'quantity' => 2,
    ])->assertCreated()->assertJsonPath('data.quantity', 2);

    expect(CartItem::where('user_id', $user->getKey())->count())->toBe(1);
});

it('updates cart item quantity', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $item = CartItem::factory()->create(['user_id' => $user->getKey(), 'quantity' => 1]);

    $this->withToken($token)->patchJson("/api/v1/cart/items/{$item->getKey()}", [
        'quantity' => 5,
    ])->assertOk()->assertJsonPath('data.quantity', 5);
});

it('cannot update another user cart item', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $item = CartItem::factory()->create();

    $this->withToken($token)->patchJson("/api/v1/cart/items/{$item->getKey()}", [
        'quantity' => 5,
    ])->assertForbidden();
});

it('removes item from cart', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $item = CartItem::factory()->create(['user_id' => $user->getKey()]);

    $this->withToken($token)->deleteJson("/api/v1/cart/items/{$item->getKey()}")->assertOk();

    expect(CartItem::find($item->getKey()))->toBeNull();
});

it('returns cart summary', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $product = Product::factory()->create([
        'selling_price' => 200,
        'discount_price' => 150,
    ]);
    $deliveryOption = DeliveryOption::factory()->create(['fee' => 30]);
    Fee::factory()->create([
        'vat' => '10.00',
        'commission' => '5.00',
        'platform_fee' => '20.00',
    ]);

    CartItem::factory()->create([
        'user_id' => $user->getKey(),
        'product_id' => $product->getKey(),
        'quantity' => 2,
        'is_selected' => true,
    ]);

    $this->withToken($token)->getJson("/api/v1/cart/summary?delivery_option_id={$deliveryOption->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.item_count', 1)
        ->assertJsonPath('data.item_total', '300.00')
        ->assertJsonPath('data.product_total', '300.00')
        ->assertJsonPath('data.delivery_fee', '30.00')
        ->assertJsonPath('data.platform_fee', '20.00')
        ->assertJsonPath('data.vat_fee', '30.00')
        ->assertJsonPath('data.commission_fee', '15.00')
        ->assertJsonPath('data.grand_total', '395.00')
        ->assertJsonPath('grand_total', '395.00');
});
