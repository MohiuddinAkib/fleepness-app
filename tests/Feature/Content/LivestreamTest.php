<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Livestream;
use App\Models\VendorProfile;
use App\Models\LivestreamLike;
use App\Models\LivestreamSave;
use App\Models\LivestreamComment;

// Public browsing
it('lists livestreams publicly', function (): void {
    Livestream::factory()->count(3)->create();

    $this->getJson('/api/livestreams')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('shows a livestream publicly', function (): void {
    $livestream = Livestream::factory()->create();

    $this->getJson("/api/livestreams/{$livestream->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.id', $livestream->getKey());
});

it('lists comments on a livestream', function (): void {
    $livestream = Livestream::factory()->create();
    LivestreamComment::factory()->count(3)->create(['livestream_id' => $livestream->getKey()]);

    $this->getJson("/api/livestreams/{$livestream->getKey()}/comments")
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

// Auth-required engagement
it('requires auth to comment on livestream', function (): void {
    $livestream = Livestream::factory()->create();
    $this->postJson("/api/livestreams/{$livestream->getKey()}/comments", ['comment' => 'Nice!'])
        ->assertUnauthorized();
});

it('posts a comment on a livestream', function (): void {
    $user = User::factory()->create();
    $livestream = Livestream::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson("/api/livestreams/{$livestream->getKey()}/comments", ['comment' => 'Going live!'])
        ->assertCreated()
        ->assertJsonPath('data.comment', 'Going live!');
});

it('cannot delete another users comment on livestream', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $comment = LivestreamComment::factory()->create();

    $this->withToken($token)
        ->deleteJson("/api/livestreams/{$comment->livestream_id}/comments/{$comment->getKey()}")
        ->assertForbidden();
});

it('deletes own comment on livestream', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $livestream = Livestream::factory()->create();
    $comment = LivestreamComment::factory()->create([
        'livestream_id' => $livestream->getKey(),
        'user_id' => $user->getKey(),
    ]);

    $this->withToken($token)
        ->deleteJson("/api/livestreams/{$livestream->getKey()}/comments/{$comment->getKey()}")
        ->assertOk();

    expect(LivestreamComment::find($comment->getKey()))->toBeNull();
});

it('likes a livestream', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $livestream = Livestream::factory()->create();

    $this->withToken($token)
        ->postJson("/api/livestreams/{$livestream->getKey()}/like")
        ->assertOk();

    expect(LivestreamLike::where('livestream_id', $livestream->getKey())->count())->toBe(1);
});

it('saves a livestream', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $livestream = Livestream::factory()->create();

    $this->withToken($token)
        ->postJson("/api/livestreams/{$livestream->getKey()}/save")
        ->assertOk();

    expect(LivestreamSave::where('livestream_id', $livestream->getKey())->count())->toBe(1);
});

// Vendor CRUD
it('requires auth to create a livestream', function (): void {
    $this->postJson('/api/me/livestreams', ['title' => 'My Stream'])
        ->assertUnauthorized();
});

it('vendor creates a livestream', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/me/livestreams', [
            'title' => 'Flash Sale Stream',
        ])
        ->assertCreated()
        ->assertJsonPath('data.title', 'Flash Sale Stream');
});

it('non-vendor cannot create livestream', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/me/livestreams', ['title' => 'My Stream'])
        ->assertForbidden();
});

it('vendor updates own livestream', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $livestream = Livestream::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/livestreams/{$livestream->getKey()}", ['title' => 'Updated Stream'])
        ->assertOk()
        ->assertJsonPath('data.title', 'Updated Stream');
});

it('vendor cannot update another vendors livestream', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $otherLivestream = Livestream::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/livestreams/{$otherLivestream->getKey()}", ['title' => 'Hijack'])
        ->assertForbidden();
});

it('vendor deletes own livestream', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $livestream = Livestream::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/me/livestreams/{$livestream->getKey()}")
        ->assertOk();

    expect(Livestream::find($livestream->getKey()))->toBeNull();
});
