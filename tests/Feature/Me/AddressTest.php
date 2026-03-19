<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Address;

it('lists own addresses', function (): void {
    $user = User::factory()->create();
    Address::factory()->count(3)->for($user)->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/me/addresses');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('stores a new address', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/me/addresses', [
        'label' => 'Home',
        'city' => 'Dhaka',
        'address_line_1' => '123 Main St',
    ]);

    $response->assertCreated()->assertJsonPath('data.label', 'Home');
    expect(Address::where('user_id', $user->getKey())->count())->toBe(1);
});

it('updates an address', function (): void {
    $user = User::factory()->create();
    $address = Address::factory()->for($user)->create(['city' => 'Old City']);
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->patchJson("/api/me/addresses/{$address->getKey()}", [
        'city' => 'New City',
    ]);

    $response->assertOk()->assertJsonPath('data.city', 'New City');
});

it('cannot update another user\'s address', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $address = Address::factory()->for($other)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->patchJson("/api/me/addresses/{$address->getKey()}", ['city' => 'X'])
        ->assertForbidden();
});

it('deletes an address', function (): void {
    $user = User::factory()->create();
    $address = Address::factory()->for($user)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->deleteJson("/api/me/addresses/{$address->getKey()}")->assertOk();

    expect(Address::find($address->getKey()))->toBeNull();
});

it('cannot delete another user\'s address', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $address = Address::factory()->for($other)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->deleteJson("/api/me/addresses/{$address->getKey()}")->assertForbidden();
});

it('sets an address as default', function (): void {
    $user = User::factory()->create();
    $address1 = Address::factory()->for($user)->asDefault()->create();
    $address2 = Address::factory()->for($user)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson("/api/me/addresses/{$address2->getKey()}/default")
        ->assertOk();

    expect($address2->fresh()->is_default)->toBeTrue();
    expect($address1->fresh()->is_default)->toBeFalse();
});

it('returns the default address on the legacy endpoint', function (): void {
    $user = User::factory()->create();
    $address = Address::factory()->for($user)->asDefault()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/addresses/default')
        ->assertOk()
        ->assertJsonPath('default_address.id', $address->getKey())
        ->assertJsonPath('data.id', $address->getKey());
});

it('returns 401 when unauthenticated', function (): void {
    $this->getJson('/api/me/addresses')->assertUnauthorized();
});
