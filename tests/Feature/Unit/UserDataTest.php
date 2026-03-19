<?php

declare(strict_types=1);

use App\Models\User;
use App\Data\UserData;

it('includes the supported image and channel fields in user data', function (): void {
    $user = User::factory()->create();
    $user
        ->addMediaFromString('banner')
        ->usingFileName('banner.jpg')
        ->toMediaCollection('banner_image');
    $user
        ->addMediaFromString('cover')
        ->usingFileName('cover.jpg')
        ->toMediaCollection('cover_image');

    $data = UserData::fromModel($user)->toArray();

    expect($data)
        ->toHaveKey('banner_image', $user->getFirstMediaUrl('banner_image'))
        ->toHaveKey('cover_image', $user->getFirstMediaUrl('cover_image'))
        ->toHaveKey('notification_channel', $user->receivesBroadcastNotificationsOn());
});
