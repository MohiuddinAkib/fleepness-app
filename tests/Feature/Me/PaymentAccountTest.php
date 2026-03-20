<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\UserPaymentAccount;

it('lists own payment accounts', function (): void {
    $user = User::factory()->create();
    UserPaymentAccount::factory()->count(2)->for($user)->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/v1/me/payment-accounts');

    $response->assertOk()->assertJsonCount(2, 'data');
});

it('stores a payment account', function (): void {
    $user = User::factory()->create();
    $method = PaymentMethod::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/v1/me/payment-accounts', [
        'payment_method_id' => $method->getKey(),
        'account_number' => '01712345678',
    ]);

    $response->assertCreated()->assertJsonPath('data.account_number', '01712345678');
    expect(UserPaymentAccount::where('user_id', $user->getKey())->count())->toBe(1);
});

it('returns 422 when payment method does not exist', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/v1/me/payment-accounts', [
        'payment_method_id' => 9999,
        'account_number' => '01712345678',
    ])->assertUnprocessable();
});

it('deletes a payment account', function (): void {
    $user = User::factory()->create();
    $account = UserPaymentAccount::factory()->for($user)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->deleteJson("/api/v1/me/payment-accounts/{$account->getKey()}")->assertOk();

    expect(UserPaymentAccount::find($account->getKey()))->toBeNull();
});

it('cannot delete another user\'s payment account', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $account = UserPaymentAccount::factory()->for($other)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->deleteJson("/api/v1/me/payment-accounts/{$account->getKey()}")->assertForbidden();
});

it('returns 401 when unauthenticated', function (): void {
    $this->getJson('/api/v1/me/payment-accounts')->assertUnauthorized();
});
