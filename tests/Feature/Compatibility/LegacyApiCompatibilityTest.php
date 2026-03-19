<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\VendorProfile;
use App\Enums\VendorOrderStatus;
use Illuminate\Support\Facades\Route;

it('returns role and seller status on legacy compatibility endpoints', function (): void {
    $user = User::factory()->create(['name' => 'Vendor User']);
    VendorProfile::factory()->for($user)->create([
        'status' => VendorStatus::Approved,
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/me/role')
        ->assertOk()
        ->assertHeader('X-Fleepness-Legacy-Endpoint', 'true')
        ->assertHeader('X-Fleepness-Migration-Key', 'me.role')
        ->assertHeader('X-Fleepness-Legacy-Path', '/api/me/role')
        ->assertHeader('X-Fleepness-Replacement-Endpoints', '/api/me,/api/me/vendor')
        ->assertJsonPath('user_id', $user->getKey())
        ->assertJsonPath('role', 'vendor')
        ->assertJsonPath('status', VendorStatus::Approved->value);

    $this->withToken($token)->getJson('/api/seller/status')
        ->assertOk()
        ->assertHeader('X-Fleepness-Legacy-Endpoint', 'true')
        ->assertHeader('X-Fleepness-Migration-Key', 'seller.status')
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
        ->assertHeader('X-Fleepness-Legacy-Endpoint', 'true')
        ->assertHeader('X-Fleepness-Migration-Key', 'notifications.index')
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
        ->assertHeader('X-Fleepness-Legacy-Endpoint', 'true')
        ->assertHeader('X-Fleepness-Migration-Key', 'user.balance-stats')
        ->assertJsonPath('user_id', $vendorUser->getKey())
        ->assertJsonPath('name', 'Vendor User')
        ->assertJsonPath('daily_balance', '50.00')
        ->assertJsonPath('weekly_balance', '50.00')
        ->assertJsonPath('monthly_balance', '50.00')
        ->assertJsonPath('lifetime_balance', '250.00');
});

it('keeps the legacy endpoint migration map aligned with route middleware', function (): void {
    $routeKeys = collect(Route::getRoutes()->getRoutes())
        ->flatMap(fn ($route): array => $route->gatherMiddleware())
        ->filter(fn (string $middleware): bool => str_starts_with($middleware, 'legacy-endpoint:'))
        ->map(fn (string $middleware): string => str($middleware)->after('legacy-endpoint:')->toString())
        ->values()
        ->all();

    $config = config('api_migration.legacy_endpoints');

    expect($config)->toBeArray();
    expect(array_keys($config))->toEqualCanonicalizing($routeKeys);

    foreach ($config as $key => $metadata) {
        expect($metadata)
            ->toHaveKeys(['path', 'replacements'])
            ->and($metadata['path'])->toBeString()->not->toBe('')
            ->and($metadata['replacements'])->toBeArray()->not->toBeEmpty();
    }
});
