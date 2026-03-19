<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

describe('POST /api/auth/register', function (): void {
    it('registers a new user and returns OTP in non-production', function (): void {
        Notification::fake();

        $response = $this->postJson('/api/auth/register', [
            'phone_number' => '01712345678',
            'name' => 'Test User',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['message', 'user' => ['id', 'name', 'phone_number'], 'otp']);

        $this->assertDatabaseHas('users', ['phone_number' => '01712345678', 'name' => 'Test User']);
    });

    it('returns 422 when phone number is already taken', function (): void {
        User::factory()->create(['phone_number' => '01712345678']);

        $response = $this->postJson('/api/auth/register', [
            'phone_number' => '01712345678',
        ]);

        $response->assertUnprocessable();
    });

    it('returns 422 when phone number is not 11 digits', function (): void {
        $response = $this->postJson('/api/auth/register', [
            'phone_number' => '0171234',
        ]);

        $response->assertUnprocessable();
    });

    it('returns 422 when phone number is missing', function (): void {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertUnprocessable();
    });

    it('registers a new user without a name', function (): void {
        Notification::fake();

        $response = $this->postJson('/api/auth/register', [
            'phone_number' => '01712345679',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.phone_number', '01712345679')
            ->assertJsonPath('user.name', '01712345679');

        $this->assertDatabaseHas('users', [
            'phone_number' => '01712345679',
            'name' => '01712345679',
        ]);
    });
});

describe('POST /api/auth/verify-otp', function (): void {
    it('verifies OTP and returns a Sanctum token', function (): void {
        $user = User::factory()->create(['phone_number' => '01712345678']);
        Cache::put("otp_{$user->phone_number}", '1111', now()->addMinutes(10));

        $response = $this->postJson('/api/auth/verify-otp', [
            'phone_number' => '01712345678',
            'otp' => '1111',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message', 'token', 'user' => ['id', 'name', 'phone_number']]);
    });

    it('returns 422 when OTP has expired', function (): void {
        User::factory()->create(['phone_number' => '01712345678']);

        $response = $this->postJson('/api/auth/verify-otp', [
            'phone_number' => '01712345678',
            'otp' => '1111',
        ]);

        $response->assertUnprocessable()
            ->assertJson(['message' => 'OTP has expired.']);
    });

    it('returns 422 when OTP is incorrect', function (): void {
        $user = User::factory()->create(['phone_number' => '01712345678']);
        Cache::put("otp_{$user->phone_number}", '1111', now()->addMinutes(10));

        $response = $this->postJson('/api/auth/verify-otp', [
            'phone_number' => '01712345678',
            'otp' => '9999',
        ]);

        $response->assertUnprocessable()
            ->assertJson(['message' => 'Invalid OTP.']);
    });

    it('returns 422 when phone number does not exist', function (): void {
        $response = $this->postJson('/api/auth/verify-otp', [
            'phone_number' => '01799999999',
            'otp' => '1111',
        ]);

        $response->assertUnprocessable();
    });
});

describe('POST /api/auth/resend-otp', function (): void {
    it('resends OTP to existing user', function (): void {
        Notification::fake();
        User::factory()->create(['phone_number' => '01712345678']);

        $response = $this->postJson('/api/auth/resend-otp', [
            'phone_number' => '01712345678',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message', 'otp']);
    });

    it('returns 422 when phone number does not exist', function (): void {
        $response = $this->postJson('/api/auth/resend-otp', [
            'phone_number' => '01799999999',
        ]);

        $response->assertUnprocessable();
    });
});

describe('POST /api/auth/login', function (): void {
    it('sends login OTP to existing user', function (): void {
        Notification::fake();
        User::factory()->create(['phone_number' => '01712345678']);

        $response = $this->postJson('/api/auth/login', [
            'phone_number' => '01712345678',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message', 'otp']);
    });

    it('returns 422 when phone number does not exist', function (): void {
        $response = $this->postJson('/api/auth/login', [
            'phone_number' => '01799999999',
        ]);

        $response->assertUnprocessable();
    });
});

describe('POST /api/auth/logout', function (): void {
    it('logs out authenticated user', function (): void {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/auth/logout');

        $response->assertOk()->assertJson(['message' => 'Logged out successfully.']);
        expect($user->tokens()->count())->toBe(0);
    });

    it('returns 401 for unauthenticated request', function (): void {
        $this->postJson('/api/auth/logout')->assertUnauthorized();
    });
});
