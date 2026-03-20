<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Product;
use App\Enums\VendorStatus;
use App\Enums\ProductStatus;
use App\Models\ShopCategory;
use App\Models\VendorReview;
use App\Models\VendorProfile;
use App\Models\VendorFollower;

it('lists approved vendors', function (): void {
    VendorProfile::factory()->count(3)->approved()->create();
    VendorProfile::factory()->count(2)->create(['status' => VendorStatus::Pending]);

    $response = $this->getJson('/api/v1/vendors');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('filters vendors by search query', function (): void {
    VendorProfile::factory()->approved()->create(['shop_name' => 'Flash Store']);
    VendorProfile::factory()->approved()->create(['shop_name' => 'Winter House']);

    $this->getJson('/api/v1/vendors?search=flash')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.shop_name', 'Flash Store');
});

it('lists similar vendors through the canonical vendor query', function (): void {
    $sharedCategory = ShopCategory::factory()->create();
    $otherCategory = ShopCategory::factory()->create();
    $sourceVendor = VendorProfile::factory()->approved()->create(['shop_category_id' => $sharedCategory->getKey()]);
    $similarVendor = VendorProfile::factory()->approved()->create(['shop_category_id' => $sharedCategory->getKey()]);
    VendorProfile::factory()->approved()->create(['shop_category_id' => $otherCategory->getKey()]);

    $this->getJson('/api/v1/vendors?similar_to_vendor_id='.$sourceVendor->getKey())
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $similarVendor->getKey());
});

it('shows an approved vendor', function (): void {
    $vendor = VendorProfile::factory()->approved()->create(['shop_name' => 'Best Shop']);

    $response = $this->getJson("/api/v1/vendors/{$vendor->getKey()}");

    $response->assertOk()->assertJsonPath('data.shop_name', 'Best Shop');
});

it('returns 404 for a pending vendor', function (): void {
    $vendor = VendorProfile::factory()->create(['status' => VendorStatus::Pending]);

    $this->getJson("/api/v1/vendors/{$vendor->getKey()}")->assertNotFound();
});

it('follows a vendor', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson("/api/v1/vendors/{$vendor->getKey()}/follow")->assertOk();

    expect(VendorFollower::where('user_id', $user->getKey())
        ->where('vendor_profile_id', $vendor->getKey())
        ->exists())->toBeTrue();
});

it('cannot follow the same vendor twice', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson("/api/v1/vendors/{$vendor->getKey()}/follow")->assertOk();
    $this->withToken($token)->postJson("/api/v1/vendors/{$vendor->getKey()}/follow")->assertOk();

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

    $this->withToken($token)->deleteJson("/api/v1/vendors/{$vendor->getKey()}/follow")->assertOk();

    expect(VendorFollower::where('user_id', $user->getKey())
        ->where('vendor_profile_id', $vendor->getKey())
        ->exists())->toBeFalse();
});

it('returns 401 when following without auth', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();

    $this->postJson("/api/v1/vendors/{$vendor->getKey()}/follow")->assertUnauthorized();
});

it('lists vendor reviews', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();
    VendorReview::factory()->count(3)->for($vendor)->create();

    $response = $this->getJson("/api/v1/vendors/{$vendor->getKey()}/reviews");

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('submits a vendor review', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson("/api/v1/vendors/{$vendor->getKey()}/reviews", [
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

    $this->withToken($token)->postJson("/api/v1/vendors/{$vendor->getKey()}/reviews", [
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
        ->deleteJson("/api/v1/vendors/{$vendor->getKey()}/reviews/{$review->getKey()}")
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
        ->deleteJson("/api/v1/vendors/{$vendor->getKey()}/reviews/{$review->getKey()}")
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

    $response = $this->withToken($token)->getJson('/api/v1/me/followings');

    $response->assertOk()->assertJsonCount(2, 'data');
});

it('filters vendor products by search query', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();
    Product::factory()->for($vendor, 'vendorProfile')->create([
        'name' => 'Flash Tee',
        'status' => ProductStatus::Active,
        'is_approved' => true,
    ]);
    Product::factory()->for($vendor, 'vendorProfile')->create([
        'name' => 'Winter Tee',
        'status' => ProductStatus::Active,
        'is_approved' => true,
    ]);

    $this->getJson("/api/v1/vendors/{$vendor->getKey()}/products?q=flash")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Flash Tee');
});

it('filters vendor products by explicit price range', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();
    Product::factory()->for($vendor, 'vendorProfile')->create([
        'name' => 'Budget Tee',
        'selling_price' => 300,
        'discount_price' => null,
        'status' => ProductStatus::Active,
        'is_approved' => true,
    ]);
    Product::factory()->for($vendor, 'vendorProfile')->create([
        'name' => 'Premium Tee',
        'selling_price' => 1500,
        'discount_price' => null,
        'status' => ProductStatus::Active,
        'is_approved' => true,
    ]);

    $this->getJson("/api/v1/vendors/{$vendor->getKey()}/products?min_price=200&max_price=500")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Budget Tee');
});

it('filters vendor products by price category', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();
    Product::factory()->for($vendor, 'vendorProfile')->create([
        'name' => 'Budget Tee',
        'selling_price' => 300,
        'discount_price' => null,
        'status' => ProductStatus::Active,
        'is_approved' => true,
    ]);
    Product::factory()->for($vendor, 'vendorProfile')->create([
        'name' => 'Premium Tee',
        'selling_price' => 1500,
        'discount_price' => null,
        'status' => ProductStatus::Active,
        'is_approved' => true,
    ]);

    $this->getJson("/api/v1/vendors/{$vendor->getKey()}/products?price_category=premium")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Premium Tee');
});
