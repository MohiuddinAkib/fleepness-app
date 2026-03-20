<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Livestream;
use App\Models\LivestreamLike;
use App\Models\LivestreamComment;

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

it('broadcasts like count updates via the LivestreamLike model event contract', function (): void {
    $user = User::factory()->create();
    $livestream = Livestream::factory()->started()->create();
    $like = LivestreamLike::factory()->create([
        'livestream_id' => $livestream->getKey(),
        'user_id' => $user->getKey(),
    ]);

    expect($like->broadcastAs('created'))->toBe('livestream_like_count_updated')
        ->and($like->broadcastAs('deleted'))->toBe('livestream_like_count_updated')
        ->and($like->broadcastAs('updated'))->toBe('livestream_like_updated')
        ->and($like->broadcastOn('created'))->toHaveCount(1)
        ->and($like->broadcastOn('updated'))->toHaveCount(0)
        ->and($like->broadcastWith('created'))->toHaveKey('likes_count');
});

it('broadcasts livestream model events with the canonical feed event names', function (): void {
    $livestream = Livestream::factory()->create();

    expect($livestream->broadcastAs('created'))->toBe('livestream_created')
        ->and($livestream->broadcastAs('updated'))->toBe('livestream_updated')
        ->and($livestream->broadcastOn('created'))->toHaveCount(1)
        ->and($livestream->broadcastOn('updated'))->toHaveCount(2)
        ->and($livestream->broadcastWith('created'))->toHaveKey('id', $livestream->getKey());
});
