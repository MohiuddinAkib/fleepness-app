<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\VendorReview;
use App\Models\VendorProfile;
use App\Models\VendorFollower;

it('lists approved vendors', function (): void {
    VendorProfile::factory()->count(3)->approved()->create();
    VendorProfile::factory()->count(2)->create(['status' => VendorStatus::Pending]);

    $response = $this->getJson('/api/vendors');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('shows an approved vendor', function (): void {
    $vendor = VendorProfile::factory()->approved()->create(['shop_name' => 'Best Shop']);

    $response = $this->getJson("/api/vendors/{$vendor->getKey()}");

    $response->assertOk()->assertJsonPath('data.shop_name', 'Best Shop');
});

it('returns 404 for a pending vendor', function (): void {
    $vendor = VendorProfile::factory()->create(['status' => VendorStatus::Pending]);

    $this->getJson("/api/vendors/{$vendor->getKey()}")->assertNotFound();
});

it('follows a vendor', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson("/api/vendors/{$vendor->getKey()}/follow")->assertOk();

    expect(VendorFollower::where('user_id', $user->getKey())
        ->where('vendor_profile_id', $vendor->getKey())
        ->exists())->toBeTrue();
});

it('cannot follow the same vendor twice', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson("/api/vendors/{$vendor->getKey()}/follow")->assertOk();
    $this->withToken($token)->postJson("/api/vendors/{$vendor->getKey()}/follow")->assertOk();

    expect(VendorFollower::where('user_id', $user->getKey())
        ->where('vendor_profile_id', $vendor->getKey())
        ->count())->toBe(1);
});

it('unfollows a vendor', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    VendorFollower::factory()->create([
        'user_id' => $user->getKey(),
        'vendor_profile_id' => $vendor->getKey(),
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->deleteJson("/api/vendors/{$vendor->getKey()}/follow")->assertOk();

    expect(VendorFollower::where('user_id', $user->getKey())
        ->where('vendor_profile_id', $vendor->getKey())
        ->exists())->toBeFalse();
});

it('returns 401 when following without auth', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();

    $this->postJson("/api/vendors/{$vendor->getKey()}/follow")->assertUnauthorized();
});

it('lists vendor reviews', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();
    VendorReview::factory()->count(3)->for($vendor)->create();

    $response = $this->getJson("/api/vendors/{$vendor->getKey()}/reviews");

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('submits a vendor review', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson("/api/vendors/{$vendor->getKey()}/reviews", [
        'rating' => 4,
        'comment' => 'Great vendor!',
    ]);

    $response->assertCreated()->assertJsonPath('data.rating', 4);
    expect(VendorReview::where('vendor_profile_id', $vendor->getKey())->count())->toBe(1);
});

it('cannot review the same vendor twice', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    VendorReview::factory()->create([
        'user_id' => $user->getKey(),
        'vendor_profile_id' => $vendor->getKey(),
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson("/api/vendors/{$vendor->getKey()}/reviews", [
        'rating' => 5,
    ])->assertUnprocessable();
});

it('deletes own vendor review', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $review = VendorReview::factory()->create([
        'user_id' => $user->getKey(),
        'vendor_profile_id' => $vendor->getKey(),
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/vendors/{$vendor->getKey()}/reviews/{$review->getKey()}")
        ->assertOk();

    expect(VendorReview::find($review->getKey()))->toBeNull();
});

it('cannot delete another user\'s review', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $review = VendorReview::factory()->create([
        'user_id' => $other->getKey(),
        'vendor_profile_id' => $vendor->getKey(),
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/vendors/{$vendor->getKey()}/reviews/{$review->getKey()}")
        ->assertForbidden();
});

it('lists following vendors', function (): void {
    $user = User::factory()->create();
    $vendors = VendorProfile::factory()->count(2)->approved()->create();
    foreach ($vendors as $vendor) {
        VendorFollower::factory()->create([
            'user_id' => $user->getKey(),
            'vendor_profile_id' => $vendor->getKey(),
        ]);
    }
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me/followings');

    $response->assertOk()->assertJsonCount(2, 'data');
});
