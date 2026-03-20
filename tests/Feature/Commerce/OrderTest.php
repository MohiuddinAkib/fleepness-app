<?php

declare(strict_types=1);

use App\Models\Fee;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\VendorOrder;
use App\Models\VendorProfile;
use App\Models\DeliveryOption;
use App\Enums\VendorOrderStatus;
use App\Notifications\OrderReceivedByVendor;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorOrderStatusChanged;

it('requires auth to place order', function (): void {
    $this->postJson('/api/v1/orders')->assertUnauthorized();
});

it('places an order from selected cart items', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $product = Product::factory()->for($vendor, 'vendorProfile')->create([
        'selling_price' => 500,
        'discount_price' => 400,
        'quantity' => 10,
    ]);
    CartItem::factory()->create([
        'user_id' => $user->getKey(),
        'product_id' => $product->getKey(),
        'quantity' => 2,
        'is_selected' => true,
    ]);
    $deliveryOption = DeliveryOption::factory()->create(['fee' => 50]);
    Fee::factory()->create(['vat' => '0.00', 'platform_fee' => '0.00', 'commission' => '0.00']);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/v1/orders', [
        'delivery_option_id' => $deliveryOption->getKey(),
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.product_total', '800.00')
        ->assertJsonPath('data.grand_total', '850.00');

    expect(Order::count())->toBe(1);
    expect(VendorOrder::count())->toBe(1);
    expect($product->fresh()->quantity)->toBe(8);
    expect(CartItem::where('user_id', $user->getKey())->count())->toBe(0);

    Notification::assertSentTo(
        $vendor->user,
        OrderReceivedByVendor::class,
        fn (OrderReceivedByVendor $notification): bool => '800.00' === $notification->vendorOrder->product_total
    );
});

it('rejects order when cart is empty', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $deliveryOption = DeliveryOption::factory()->create();

    $this->withToken($token)->postJson('/api/v1/orders', [
        'delivery_option_id' => $deliveryOption->getKey(),
    ])->assertUnprocessable();
});

it('creates vendor orders grouped by vendor', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $vendor1 = VendorProfile::factory()->approved()->create();
    $vendor2 = VendorProfile::factory()->approved()->create();
    $product1 = Product::factory()->for($vendor1, 'vendorProfile')->create(['selling_price' => 100, 'quantity' => 10]);
    $product2 = Product::factory()->for($vendor2, 'vendorProfile')->create(['selling_price' => 200, 'quantity' => 10]);

    CartItem::factory()->create(['user_id' => $user->getKey(), 'product_id' => $product1->getKey(), 'quantity' => 1]);
    CartItem::factory()->create(['user_id' => $user->getKey(), 'product_id' => $product2->getKey(), 'quantity' => 1]);

    $deliveryOption = DeliveryOption::factory()->create(['fee' => 0]);
    Fee::factory()->create(['vat' => '0.00', 'platform_fee' => '0.00', 'commission' => '0.00']);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/v1/orders', [
        'delivery_option_id' => $deliveryOption->getKey(),
    ])->assertCreated();

    expect(VendorOrder::count())->toBe(2);
    expect(Order::first()->is_multi_vendor)->toBeTrue();
});

it('lists own orders', function (): void {
    $user = User::factory()->create();
    Order::factory()->count(3)->create(['user_id' => $user->getKey()]);
    Order::factory()->count(2)->create(); // other users
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/me/orders')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('filters own orders by order code', function (): void {
    $user = User::factory()->create();
    $matchingOrder = Order::factory()->create([
        'user_id' => $user->getKey(),
        'order_number' => 'MATCH-ORDER-001',
    ]);
    Order::factory()->create([
        'user_id' => $user->getKey(),
        'order_number' => 'OTHER-ORDER-002',
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/me/orders?order_code=MATCH')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $matchingOrder->getKey());
});

it('shows own order', function (): void {
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson("/api/v1/me/orders/{$order->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.order_number', $order->order_number);
});

it('cannot see another user order', function (): void {
    $user = User::factory()->create();
    $order = Order::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson("/api/v1/me/orders/{$order->getKey()}")->assertForbidden();
});

it('lists vendor orders', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    VendorOrder::factory()->count(3)->create(['vendor_profile_id' => $vendor->getKey()]);
    VendorOrder::factory()->count(2)->create(); // other vendors
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/me/vendor-orders')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('filters vendor orders by status', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $matchingOrder = VendorOrder::factory()->create([
        'vendor_profile_id' => $vendor->getKey(),
        'status' => VendorOrderStatus::Pending,
    ]);
    VendorOrder::factory()->create([
        'vendor_profile_id' => $vendor->getKey(),
        'status' => VendorOrderStatus::Packaging,
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/me/vendor-orders?status=pending')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $matchingOrder->getKey());
});

it('vendor can accept pending order', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $customer = User::factory()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'vendor_profile_id' => $vendor->getKey(),
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::Pending,
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patchJson("/api/v1/me/vendor-orders/{$vendorOrder->getKey()}/accept")
        ->assertOk();

    expect($vendorOrder->fresh()->status)->toBe(VendorOrderStatus::Packaging);

    Notification::assertSentTo($customer, VendorOrderStatusChanged::class);
});

it('vendor can reject pending order', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $customer = User::factory()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'vendor_profile_id' => $vendor->getKey(),
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::Pending,
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patchJson("/api/v1/me/vendor-orders/{$vendorOrder->getKey()}/reject")
        ->assertOk();

    expect($vendorOrder->fresh()->status)->toBe(VendorOrderStatus::Rejected);

    Notification::assertSentTo($customer, VendorOrderStatusChanged::class);
});

it('cannot accept an already-accepted order', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->for($user)->approved()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'vendor_profile_id' => $vendor->getKey(),
        'status' => VendorOrderStatus::Packaging,
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patchJson("/api/v1/me/vendor-orders/{$vendorOrder->getKey()}/accept")
        ->assertUnprocessable();
});
