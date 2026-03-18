<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\VendorProfile;

it('returns 404 when user has no vendor profile', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/me/vendor')->assertNotFound();
});

it('returns vendor profile', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create(['shop_name' => 'My Shop']);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me/vendor');

    $response->assertOk()->assertJsonPath('data.shop_name', 'My Shop');
});

it('updates vendor profile', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->patchJson('/api/me/vendor', [
        'shop_name' => 'Updated Shop',
        'description' => 'A great shop',
    ]);

    $response->assertOk()->assertJsonPath('data.shop_name', 'Updated Shop');
});

it('applies to become a vendor', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/vendor-application', [
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

    $this->withToken($token)->postJson('/api/vendor-application', [
        'shop_name' => 'Another Shop',
    ])->assertUnprocessable();
});

it('returns vendor application status', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->create(['status' => VendorStatus::Pending]);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/vendor-application/status');

    $response->assertOk()->assertJsonPath('data.status', VendorStatus::Pending->value);
});

it('returns 401 for unauthenticated vendor application', function (): void {
    $this->postJson('/api/vendor-application', ['shop_name' => 'X'])->assertUnauthorized();
});

it('returns vendor balance', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->for($user)->approved()->create(['balance' => '250.00']);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me/balance');

    $response->assertOk()->assertJsonPath('data.balance', '250.00');
});
