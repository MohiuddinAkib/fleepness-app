<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        ->getJson('/api/v1/me')
        ->assertOk();

    $json = $response->json();

    expect(data_get($json, 'banner_image') ?? data_get($json, 'data.banner_image'))
        ->toBe($user->getFirstMediaUrl('banner_image'))
        ->and(data_get($json, 'cover_image') ?? data_get($json, 'data.cover_image'))
        ->toBe($user->getFirstMediaUrl('cover_image'))
        ->and(data_get($json, 'notification_channel') ?? data_get($json, 'data.notification_channel'))
        ->toBe($user->receivesBroadcastNotificationsOn());
});

it('updates phone number and media-backed profile images', function (): void {
    Storage::fake('public');

    $user = User::factory()->create([
        'phone_number' => '01710000000',
    ]);

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->patch('/api/v1/me', [
        'name' => 'Updated User',
        'phone_number' => '01719999999',
        'banner_image' => UploadedFile::fake()->image('banner.jpg'),
        'cover_image' => UploadedFile::fake()->image('cover.jpg'),
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Updated User')
        ->assertJsonPath('data.phone_number', '01719999999');

    $user->refresh();

    expect($user->getFirstMedia('banner_image'))->not->toBeNull()
        ->and($user->getFirstMedia('cover_image'))->not->toBeNull();
});
