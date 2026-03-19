<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\VendorOrder;
use App\Models\VendorProfile;
use App\Enums\VendorOrderStatus;

it('returns 404 when user has no vendor profile', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/me/vendors')->assertNotFound();
});

it('returns vendor profile', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create(['shop_name' => 'My Shop']);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me/vendors');

    $response->assertOk()->assertJsonPath('data.shop_name', 'My Shop');
});

it('updates vendor profile', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->patchJson('/api/me/vendors', [
        'shop_name' => 'Updated Shop',
        'description' => 'A great shop',
    ]);

    $response->assertOk()->assertJsonPath('data.shop_name', 'Updated Shop');
});

it('applies to become a vendor', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/vendor-applications', [
        'shop_name' => 'My New Shop',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.shop_name', 'My New Shop')
        ->assertJsonPath('data.status', VendorStatus::Pending->value);

    expect(VendorProfile::where('user_id', $user->getKey())->count())->toBe(1);
});

it('cannot apply twice', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/vendor-applications', [
        'shop_name' => 'Another Shop',
    ])->assertUnprocessable();
});

it('returns vendor application status', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->create(['status' => VendorStatus::Pending]);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/vendor-applications/status');

    $response->assertOk()->assertJsonPath('data.status', VendorStatus::Pending->value);
});

it('returns 401 for unauthenticated vendor application', function (): void {
    $this->postJson('/api/vendor-applications', ['shop_name' => 'X'])->assertUnauthorized();
});

it('returns vendor balance', function (): void {
    $user = User::factory()->create();
    $vendorProfile = VendorProfile::factory()->for($user)->approved()->create([
        'balance' => '250.00',
        'total_sales' => '1200.00',
        'withdrawn_amount' => '500.00',
    ]);
    VendorOrder::factory()->create([
        'vendor_profile_id' => $vendorProfile->getKey(),
        'status' => VendorOrderStatus::Delivered,
        'balance' => '50.00',
        'created_at' => now(),
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me/balances');

    $response->assertOk()
        ->assertJsonPath('data.balance', '250.00')
        ->assertJsonPath('data.total_sales', '1200.00')
        ->assertJsonPath('data.withdrawn_amount', '500.00')
        ->assertJsonPath('data.daily_balance', '50.00')
        ->assertJsonPath('data.weekly_balance', '50.00')
        ->assertJsonPath('data.monthly_balance', '50.00')
        ->assertJsonPath('data.lifetime_balance', '250.00');
});

it('returns vendor followers on the canonical me endpoint', function (): void {
    $vendorUser = User::factory()->create();
    $vendorProfile = VendorProfile::factory()->for($vendorUser)->approved()->create();
    $follower = User::factory()->create(['name' => 'Follower One']);
    $token = $vendorUser->createToken('test')->plainTextToken;

    $vendorProfile->followers()->create([
        'user_id' => $follower->getKey(),
    ]);

    $this->withToken($token)->getJson('/api/me/vendors/followers')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $follower->getKey());
});
