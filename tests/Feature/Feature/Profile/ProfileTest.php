<?php

declare(strict_types=1);

use App\Models\User;

it('returns media-backed image fields on the authenticated profile payload', function (): void {
    $user = User::factory()->create();
    $user
        ->addMediaFromString('banner')
        ->usingFileName('banner.jpg')
        ->toMediaCollection('banner_image');
    $user
        ->addMediaFromString('cover')
        ->usingFileName('cover.jpg')
        ->toMediaCollection('cover_image');

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/me')
        ->assertOk();

    $json = $response->json();

    expect(data_get($json, 'banner_image') ?? data_get($json, 'data.banner_image'))
        ->toBe($user->getFirstMediaUrl('banner_image'))
        ->and(data_get($json, 'cover_image') ?? data_get($json, 'data.cover_image'))
        ->toBe($user->getFirstMediaUrl('cover_image'))
        ->and(data_get($json, 'notification_channel') ?? data_get($json, 'data.notification_channel'))
        ->toBe($user->receivesBroadcastNotificationsOn());
});
