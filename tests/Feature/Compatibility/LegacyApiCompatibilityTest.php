<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\VendorProfile;
use App\Enums\VendorOrderStatus;

it('returns role and seller status on legacy compatibility endpoints', function (): void {
    $user = User::factory()->create(['name' => 'Vendor User']);
    VendorProfile::factory()->for($user)->create([
        'status' => VendorStatus::Approved,
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/me/role')
        ->assertOk()
        ->assertJsonPath('user_id', $user->getKey())
        ->assertJsonPath('role', 'vendor')
        ->assertJsonPath('status', VendorStatus::Approved->value);

    $this->withToken($token)->getJson('/api/seller/status')
        ->assertOk()
        ->assertJsonPath('status', VendorStatus::Approved->value);
});

it('returns notifications on the legacy notifications endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'legacy.test',
        'data' => ['message' => 'Test notification'],
    ]);

    $this->withToken($token)->getJson('/api/notifications')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('notifications.total', 1);
});

it('returns followers on the legacy endpoint', function (): void {
    $vendorUser = User::factory()->create();
    $vendorProfile = VendorProfile::factory()->for($vendorUser)->approved()->create();
    $follower = User::factory()->create(['name' => 'Follower One']);
    $token = $vendorUser->createToken('test')->plainTextToken;

    $vendorProfile->followers()->create([
        'user_id' => $follower->getKey(),
    ]);

    $this->withToken($token)->getJson('/api/followers')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $follower->getKey());
});

it('returns vendor balance stats on the legacy endpoint', function (): void {
    $vendorUser = User::factory()->create(['name' => 'Vendor User']);
    $vendorProfile = VendorProfile::factory()->for($vendorUser)->approved()->create([
        'balance' => '250.00',
    ]);
    $token = $vendorUser->createToken('test')->plainTextToken;

    VendorOrder::factory()->create([
        'vendor_profile_id' => $vendorProfile->getKey(),
        'status' => VendorOrderStatus::Delivered,
        'balance' => '50.00',
        'created_at' => now(),
    ]);

    $this->withToken($token)->getJson('/api/user/balance-stats')
        ->assertOk()
        ->assertJsonPath('user_id', $vendorUser->getKey())
        ->assertJsonPath('name', 'Vendor User')
        ->assertJsonPath('daily_balance', '50.00')
        ->assertJsonPath('weekly_balance', '50.00')
        ->assertJsonPath('monthly_balance', '50.00')
        ->assertJsonPath('lifetime_balance', '250.00');
});
