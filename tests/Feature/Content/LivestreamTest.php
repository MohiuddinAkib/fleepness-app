<?php

declare(strict_types=1);

use App\Models\User;
use Livekit\EgressInfo;
use App\Models\Livestream;
use App\Models\VendorProfile;
use App\Models\LivestreamLike;
use App\Models\LivestreamSave;
use App\Enums\LivestreamStatus;
use App\Models\LivestreamComment;
use App\Services\LivestreamService;

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

it('returns liked livestreams on the legacy endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $likedLivestream = Livestream::factory()->create();
    $otherLivestream = Livestream::factory()->create();

    LivestreamLike::factory()->create([
        'user_id' => $user->getKey(),
        'livestream_id' => $likedLivestream->getKey(),
    ]);
    LivestreamLike::factory()->create([
        'user_id' => User::factory()->create()->getKey(),
        'livestream_id' => $otherLivestream->getKey(),
    ]);

    $this->withToken($token)->getJson('/api/lives/liked')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $likedLivestream->getKey());
});

it('returns saved livestreams on the legacy endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $savedLivestream = Livestream::factory()->create();
    $otherLivestream = Livestream::factory()->create();

    LivestreamSave::factory()->create([
        'user_id' => $user->getKey(),
        'livestream_id' => $savedLivestream->getKey(),
    ]);
    LivestreamSave::factory()->create([
        'user_id' => User::factory()->create()->getKey(),
        'livestream_id' => $otherLivestream->getKey(),
    ]);

    $this->withToken($token)->getJson('/api/lives/saved')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $savedLivestream->getKey());
});

it('returns livestream likes count on the legacy endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $livestream = Livestream::factory()->create();

    LivestreamLike::factory()->count(2)->create(['livestream_id' => $livestream->getKey()]);

    $this->withToken($token)->getJson("/api/lives/{$livestream->getKey()}/likes-count")
        ->assertOk()
        ->assertJsonPath('likes_count', 2);
});

// Vendor CRUD
it('requires auth to create a livestream', function (): void {
    $this->postJson('/api/me/livestreams', ['title' => 'My Stream'])
        ->assertUnauthorized();
});

it('vendor creates a livestream and receives publisher token', function (): void {
    $fakeEgress = tap(new EgressInfo)->setEgressId('egress-123');
    $this->mock(LivestreamService::class)
        ->shouldReceive('generatePublisherToken')->once()->andReturn('fake-publisher-token')
        ->shouldReceive('startRecording')->once()->andReturn($fakeEgress);

    $user = User::factory()->create();
    VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/me/livestreams', ['title' => 'Flash Sale Stream'])
        ->assertCreated()
        ->assertJsonPath('data.title', 'Flash Sale Stream')
        ->assertJsonPath('data.status', LivestreamStatus::Started->value)
        ->assertJsonPath('token', 'fake-publisher-token');
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

it('vendor starts a scheduled livestream via update', function (): void {
    $fakeEgress = tap(new EgressInfo)->setEgressId('egress-456');
    $this->mock(LivestreamService::class)
        ->shouldReceive('generatePublisherToken')->once()->andReturn('start-token')
        ->shouldReceive('startRecording')->once()->andReturn($fakeEgress);

    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $livestream = Livestream::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/livestreams/{$livestream->getKey()}", ['status' => 'started'])
        ->assertOk()
        ->assertJsonPath('data.status', LivestreamStatus::Started->value)
        ->assertJsonPath('token', 'start-token');

    expect($livestream->fresh()->started_at)->not->toBeNull();
});

it('vendor cannot start an already started livestream', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $livestream = Livestream::factory()->started()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/livestreams/{$livestream->getKey()}", ['status' => 'started'])
        ->assertUnprocessable();
});

it('vendor ends a started livestream via update', function (): void {
    $this->mock(LivestreamService::class)
        ->shouldReceive('stopRecording')->never();

    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $livestream = Livestream::factory()->started()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/livestreams/{$livestream->getKey()}", ['status' => 'finished'])
        ->assertOk()
        ->assertJsonPath('data.status', LivestreamStatus::Finished->value);

    $fresh = $livestream->fresh();
    expect($fresh->ended_at)->not->toBeNull();
    expect($fresh->total_duration)->toBeGreaterThanOrEqual(0);
});

it('vendor cannot update a finished livestream', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $livestream = Livestream::factory()->finished()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/livestreams/{$livestream->getKey()}", ['title' => 'Too Late'])
        ->assertUnprocessable();
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
