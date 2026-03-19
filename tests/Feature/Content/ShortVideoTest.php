<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\ShortVideo;
use App\Models\VendorProfile;
use App\Models\ShortVideoLike;
use App\Models\ShortVideoSave;
use App\Models\ShortVideoComment;

// Public browsing
it('lists short videos publicly', function (): void {
    ShortVideo::factory()->count(3)->create();

    $this->getJson('/api/short-videos')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('shows a short video publicly', function (): void {
    $video = ShortVideo::factory()->create();

    $this->getJson("/api/short-videos/{$video->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.id', $video->getKey());
});

it('lists comments on a short video', function (): void {
    $video = ShortVideo::factory()->create();
    ShortVideoComment::factory()->count(2)->create(['short_video_id' => $video->getKey()]);

    $this->getJson("/api/short-videos/{$video->getKey()}/comments")
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

// Auth-required engagement
it('requires auth to comment on short video', function (): void {
    $video = ShortVideo::factory()->create();
    $this->postJson("/api/short-videos/{$video->getKey()}/comments", ['comment' => 'Nice!'])
        ->assertUnauthorized();
});

it('posts a comment on a short video', function (): void {
    $user = User::factory()->create();
    $video = ShortVideo::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson("/api/short-videos/{$video->getKey()}/comments", ['comment' => 'Great video!'])
        ->assertCreated()
        ->assertJsonPath('data.comment', 'Great video!');
});

it('cannot delete another users comment', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $comment = ShortVideoComment::factory()->create();

    $this->withToken($token)
        ->deleteJson("/api/short-videos/{$comment->short_video_id}/comments/{$comment->getKey()}")
        ->assertForbidden();
});

it('deletes own comment on short video', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $video = ShortVideo::factory()->create();
    $comment = ShortVideoComment::factory()->create([
        'short_video_id' => $video->getKey(),
        'user_id' => $user->getKey(),
    ]);

    $this->withToken($token)
        ->deleteJson("/api/short-videos/{$video->getKey()}/comments/{$comment->getKey()}")
        ->assertOk();

    expect(ShortVideoComment::find($comment->getKey()))->toBeNull();
});

it('likes a short video', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $video = ShortVideo::factory()->create(['likes_count' => 0]);

    $this->withToken($token)
        ->postJson("/api/short-videos/{$video->getKey()}/like")
        ->assertOk()
        ->assertJsonPath('liked', true)
        ->assertJsonPath('like_count', 1);

    expect(ShortVideoLike::where('short_video_id', $video->getKey())->count())->toBe(1);
    expect($video->fresh()->likes_count)->toBe(1);
});

it('toggles a short video like without inflating likes count', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $video = ShortVideo::factory()->create(['likes_count' => 0]);

    $this->withToken($token)->postJson("/api/short-videos/{$video->getKey()}/like")->assertOk();

    $this->withToken($token)
        ->postJson("/api/short-videos/{$video->getKey()}/like")
        ->assertOk()
        ->assertJsonPath('liked', false)
        ->assertJsonPath('like_count', 0);

    expect(ShortVideoLike::where('short_video_id', $video->getKey())->count())->toBe(0);
    expect($video->fresh()->likes_count)->toBe(0);
});

it('saves a short video', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $video = ShortVideo::factory()->create();

    $this->withToken($token)
        ->postJson("/api/short-videos/{$video->getKey()}/save")
        ->assertOk()
        ->assertJsonPath('saved', true)
        ->assertJsonPath('save_count', 1);

    expect(ShortVideoSave::where('short_video_id', $video->getKey())->count())->toBe(1);
});

it('returns saved shorts on the legacy endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $savedVideo = ShortVideo::factory()->create();
    $otherVideo = ShortVideo::factory()->create();

    ShortVideoSave::factory()->create([
        'user_id' => $user->getKey(),
        'short_video_id' => $savedVideo->getKey(),
    ]);
    ShortVideoSave::factory()->create([
        'user_id' => User::factory()->create()->getKey(),
        'short_video_id' => $otherVideo->getKey(),
    ]);

    $this->withToken($token)->getJson('/api/shorts/saved')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $savedVideo->getKey());
});

it('returns saved shorts on the canonical me endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $savedVideo = ShortVideo::factory()->create();
    $otherVideo = ShortVideo::factory()->create();

    ShortVideoSave::factory()->create([
        'user_id' => $user->getKey(),
        'short_video_id' => $savedVideo->getKey(),
    ]);
    ShortVideoSave::factory()->create([
        'user_id' => User::factory()->create()->getKey(),
        'short_video_id' => $otherVideo->getKey(),
    ]);

    $this->withToken($token)->getJson('/api/me/short-videos/saved')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $savedVideo->getKey());
});

// Vendor CRUD
it('requires auth to create a short video', function (): void {
    $this->postJson('/api/me/short-videos', ['title' => 'My Video'])
        ->assertUnauthorized();
});

it('vendor creates a short video', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/me/short-videos', [
            'title' => 'Amazing Deal',
            'description' => 'Check this out',
        ])
        ->assertCreated()
        ->assertJsonPath('data.title', 'Amazing Deal');
});

it('non-vendor cannot create short video', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/me/short-videos', ['title' => 'My Video'])
        ->assertForbidden();
});

it('vendor updates own short video', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $video = ShortVideo::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/short-videos/{$video->getKey()}", ['title' => 'Updated Title'])
        ->assertOk()
        ->assertJsonPath('data.title', 'Updated Title');
});

it('vendor cannot update another vendors short video', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $otherVideo = ShortVideo::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/me/short-videos/{$otherVideo->getKey()}", ['title' => 'Hijack'])
        ->assertForbidden();
});

it('vendor deletes own short video', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $video = ShortVideo::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/me/short-videos/{$video->getKey()}")
        ->assertOk();

    expect(ShortVideo::find($video->getKey()))->toBeNull();
});
