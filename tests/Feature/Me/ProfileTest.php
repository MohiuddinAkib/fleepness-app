<?php

declare(strict_types=1);

use App\Models\User;

it('returns own profile', function (): void {
    $user = User::factory()->create(['name' => 'John Doe']);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me');

    $response->assertOk()->assertJsonStructure([
        'data' => ['id', 'name', 'phone_number'],
    ])->assertJsonPath('data.name', 'John Doe');
});

it('returns 401 when unauthenticated', function (): void {
    $this->getJson('/api/me')->assertUnauthorized();
});

it('updates own profile', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->patchJson('/api/me', [
        'name' => 'Updated Name',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Updated Name')
        ->assertJsonPath('message', 'Profile updated.');

    expect($user->fresh()->name)->toBe('Updated Name');
});

it('only updates provided fields', function (): void {
    $user = User::factory()->create(['name' => 'Original Name', 'email' => 'original@test.com']);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patchJson('/api/me', ['name' => 'New Name'])->assertOk();

    expect($user->fresh())
        ->name->toBe('New Name')
        ->email->toBe('original@test.com');
});

it('returns 401 updating profile when unauthenticated', function (): void {
    $this->patchJson('/api/me', ['name' => 'X'])->assertUnauthorized();
});
