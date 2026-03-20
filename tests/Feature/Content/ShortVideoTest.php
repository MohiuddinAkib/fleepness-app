<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Product;
use App\Models\ShortVideo;
use App\Models\VendorProfile;
use App\Models\ShortVideoLike;
use App\Models\ShortVideoSave;
use App\Models\ShortVideoComment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// Public browsing
it('lists short videos publicly', function (): void {
    ShortVideo::factory()->count(3)->create();

    $this->getJson('/api/v1/short-videos')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('shows a short video publicly', function (): void {
    $video = ShortVideo::factory()->create();

    $this->getJson("/api/v1/short-videos/{$video->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.id', $video->getKey());
});

it('lists comments on a short video', function (): void {
    $video = ShortVideo::factory()->create();
    ShortVideoComment::factory()->count(2)->create(['short_video_id' => $video->getKey()]);

    $this->getJson("/api/v1/short-videos/{$video->getKey()}/comments")
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

// Auth-required engagement
it('requires auth to comment on short video', function (): void {
    $video = ShortVideo::factory()->create();
    $this->postJson("/api/v1/short-videos/{$video->getKey()}/comments", ['comment' => 'Nice!'])
        ->assertUnauthorized();
});

it('posts a comment on a short video', function (): void {
    $user = User::factory()->create();
    $video = ShortVideo::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson("/api/v1/short-videos/{$video->getKey()}/comments", ['comment' => 'Great video!'])
        ->assertCreated()
        ->assertJsonPath('data.comment', 'Great video!');
});

it('cannot delete another users comment', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $comment = ShortVideoComment::factory()->create();

    $this->withToken($token)
        ->deleteJson("/api/v1/short-videos/{$comment->short_video_id}/comments/{$comment->getKey()}")
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
        ->deleteJson("/api/v1/short-videos/{$video->getKey()}/comments/{$comment->getKey()}")
        ->assertOk();

    expect(ShortVideoComment::find($comment->getKey()))->toBeNull();
});

it('likes a short video', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $video = ShortVideo::factory()->create(['likes_count' => 0]);

    $this->withToken($token)
        ->postJson("/api/v1/short-videos/{$video->getKey()}/like")
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

    $this->withToken($token)->postJson("/api/v1/short-videos/{$video->getKey()}/like")->assertOk();

    $this->withToken($token)
        ->postJson("/api/v1/short-videos/{$video->getKey()}/like")
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
        ->postJson("/api/v1/short-videos/{$video->getKey()}/save")
        ->assertOk()
        ->assertJsonPath('saved', true)
        ->assertJsonPath('save_count', 1);

    expect(ShortVideoSave::where('short_video_id', $video->getKey())->count())->toBe(1);
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

    $this->withToken($token)->getJson('/api/v1/me/short-videos/saved')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $savedVideo->getKey());
});

// Vendor CRUD
it('requires auth to create a short video', function (): void {
    $this->postJson('/api/v1/me/short-videos', ['title' => 'My Video'])
        ->assertUnauthorized();
});

it('vendor creates a short video', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    $vendorProfile = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $products = Product::factory()->count(2)->create(['vendor_profile_id' => $vendorProfile->getKey()]);
    $token = $user->createToken('test')->plainTextToken;
    $video = UploadedFile::fake()->create('short.mp4', 1024, 'video/mp4');
    $thumbnail = UploadedFile::fake()->image('short.jpg');

    $this->withToken($token)
        ->post('/api/v1/me/short-videos', [
            'title' => 'Amazing Deal',
            'description' => 'Check this out',
            'video' => $video,
            'thumbnail' => $thumbnail,
            'product_ids' => $products->pluck('id')->all(),
        ])
        ->assertCreated()
        ->assertJsonPath('data.title', 'Amazing Deal')
        ->assertJsonCount(2, 'data.products');

    $shortVideo = ShortVideo::query()->latest('id')->firstOrFail();

    expect($shortVideo->getFirstMedia('video'))->not->toBeNull();
    expect($shortVideo->getFirstMedia('thumbnail'))->not->toBeNull();
    expect($shortVideo->products()->pluck('products.id')->all())->toEqualCanonicalizing($products->pluck('id')->all());
});

it('non-vendor cannot create short video', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->post('/api/v1/me/short-videos', [
            'title' => 'My Video',
            'video' => UploadedFile::fake()->create('short.mp4', 1024, 'video/mp4'),
        ])
        ->assertForbidden();
});

it('vendor updates own short video', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $video = ShortVideo::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $video->addMedia(UploadedFile::fake()->create('old.mp4', 512, 'video/mp4'))->toMediaCollection('video');
    $existingProduct = Product::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $replacementProducts = Product::factory()->count(2)->create(['vendor_profile_id' => $vendor->getKey()]);
    $video->products()->sync([$existingProduct->getKey()]);
    $token = $user->createToken('test')->plainTextToken;
    $newVideo = UploadedFile::fake()->create('updated.mp4', 1024, 'video/mp4');
    $thumbnail = UploadedFile::fake()->image('updated.jpg');

    $this->withToken($token)
        ->patch("/api/v1/me/short-videos/{$video->getKey()}", [
            'title' => 'Updated Title',
            'video' => $newVideo,
            'thumbnail' => $thumbnail,
            'product_ids' => $replacementProducts->pluck('id')->all(),
        ])
        ->assertOk()
        ->assertJsonPath('data.title', 'Updated Title')
        ->assertJsonCount(2, 'data.products');

    $video->refresh();

    expect($video->getMedia('video'))->toHaveCount(1);
    expect($video->getFirstMedia('thumbnail'))->not->toBeNull();
    expect($video->products()->pluck('products.id')->all())->toEqualCanonicalizing($replacementProducts->pluck('id')->all());
});

it('vendor cannot update another vendors short video', function (): void {
    $user = User::factory()->create();
    VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $otherVideo = ShortVideo::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->patchJson("/api/v1/me/short-videos/{$otherVideo->getKey()}", ['title' => 'Hijack'])
        ->assertForbidden();
});

it('vendor deletes own short video', function (): void {
    $user = User::factory()->create();
    $vendor = VendorProfile::factory()->approved()->create(['user_id' => $user->getKey()]);
    $video = ShortVideo::factory()->create(['vendor_profile_id' => $vendor->getKey()]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->deleteJson("/api/v1/me/short-videos/{$video->getKey()}")
        ->assertOk();

    expect(ShortVideo::find($video->getKey()))->toBeNull();
});
