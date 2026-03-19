<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;

it('lists notifications on the canonical me endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $readNotification = $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'legacy.read',
        'data' => ['message' => 'Read notification'],
        'read_at' => now(),
    ]);

    $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'legacy.unread',
        'data' => ['message' => 'Unread notification'],
    ]);

    $this->withToken($token)
        ->getJson('/api/me/notifications?type=read&per_page=10')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $readNotification->getKey())
        ->assertJsonPath('data.0.type', 'legacy.read')
        ->assertJsonPath('data.0.data.message', 'Read notification');
});

it('marks all notifications as read on the canonical me endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'legacy.unread',
        'data' => ['message' => 'Unread notification'],
    ]);

    $this->withToken($token)
        ->postJson('/api/me/notifications/read')
        ->assertOk()
        ->assertJsonPath('message', 'Notifications marked as read.');

    expect($user->fresh()->unreadNotifications)->toHaveCount(0);
});

it('marks a single notification as read on the canonical me endpoint', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $notification = $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'legacy.unread',
        'data' => ['message' => 'Unread notification'],
    ]);

    $this->withToken($token)
        ->postJson("/api/me/notifications/{$notification->getKey()}/read")
        ->assertOk()
        ->assertJsonPath('message', 'Notification marked as read.');

    expect($notification->fresh()->read())->toBeTrue();
});
