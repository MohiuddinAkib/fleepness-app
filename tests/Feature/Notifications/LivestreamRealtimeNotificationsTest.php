<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Livestream;
use App\Models\LivestreamComment;
use App\Notifications\LivestreamLikeCountChangedNotification;

beforeEach(function (): void {
    config()->set('broadcasting.default', 'log');
});

it('broadcasts livestream comment model events with the canonical room event contract', function (): void {
    $user = User::factory()->create();
    $livestream = Livestream::factory()->create();
    $comment = LivestreamComment::factory()->create([
        'livestream_id' => $livestream->getKey(),
        'user_id' => $user->getKey(),
        'comment' => 'Going live!',
    ]);

    expect($comment->broadcastAs('created'))->toBe('livestream_comment_created')
        ->and($comment->broadcastOn('created'))->toHaveCount(1)
        ->and($comment->broadcastWith('created'))->toBe([
            'commenter' => [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->getFirstMediaUrl('cover_image') ?: null,
                'phone_number' => $user->phone_number,
            ],
            'comment' => [
                'id' => $comment->getKey(),
                'title' => 'Going live!',
            ],
        ]);
});

it('dispatches a livestream like count notification when a livestream is liked', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $livestream = Livestream::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson("/api/livestreams/{$livestream->getKey()}/like")
        ->assertOk()
        ->assertJsonPath('message', 'Liked.');

    Notification::assertSentTo(
        $livestream->fresh(),
        LivestreamLikeCountChangedNotification::class,
        fn (LivestreamLikeCountChangedNotification $notification): bool => 'livestream_like_count_updated' === $notification->broadcastAs()
    );
});

it('broadcasts livestream model events with the canonical feed event names', function (): void {
    $livestream = Livestream::factory()->create();

    expect($livestream->broadcastAs('created'))->toBe('livestream_created')
        ->and($livestream->broadcastAs('updated'))->toBe('livestream_updated')
        ->and($livestream->broadcastOn('created'))->toHaveCount(1)
        ->and($livestream->broadcastOn('updated'))->toHaveCount(2)
        ->and($livestream->broadcastWith('created'))->toHaveKey('id', $livestream->getKey());
});
