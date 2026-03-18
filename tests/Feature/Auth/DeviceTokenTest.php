<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\DeviceToken;

describe('POST /api/auth/device-tokens', function (): void {
    it('stores a device token for authenticated user', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/auth/device-tokens', [
            'token' => 'fcm-token-abc-123',
            'platform' => 'android',
        ]);

        $response->assertCreated()->assertJson(['message' => 'Device token registered.']);

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->getKey(),
            'token' => 'fcm-token-abc-123',
            'platform' => 'android',
        ]);
    });

    it('does not duplicate an existing token', function (): void {
        $user = User::factory()->create();
        DeviceToken::factory()->create(['user_id' => $user->getKey(), 'token' => 'existing-token']);

        $this->actingAs($user)->postJson('/api/auth/device-tokens', [
            'token' => 'existing-token',
        ]);

        expect(DeviceToken::where('token', 'existing-token')->count())->toBe(1);
    });

    it('returns 401 for unauthenticated request', function (): void {
        $this->postJson('/api/auth/device-tokens', ['token' => 'some-token'])->assertUnauthorized();
    });
});

describe('DELETE /api/auth/device-tokens/{deviceToken}', function (): void {
    it('removes a device token belonging to authenticated user', function (): void {
        $user = User::factory()->create();
        $token = DeviceToken::factory()->create(['user_id' => $user->getKey()]);

        $response = $this->actingAs($user)->deleteJson("/api/auth/device-tokens/{$token->getKey()}");

        $response->assertOk()->assertJson(['message' => 'Device token removed.']);
        $this->assertDatabaseMissing('device_tokens', ['id' => $token->getKey()]);
    });

    it('returns 403 when deleting another user\'s device token', function (): void {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $token = DeviceToken::factory()->create(['user_id' => $other->getKey()]);

        $this->actingAs($user)->deleteJson("/api/auth/device-tokens/{$token->getKey()}")->assertForbidden();
    });

    it('returns 401 for unauthenticated request', function (): void {
        $token = DeviceToken::factory()->create();
        $this->deleteJson("/api/auth/device-tokens/{$token->getKey()}")->assertUnauthorized();
    });
});
