<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\Transaction;
use App\Enums\TransactionStatus;
use App\Notifications\WithdrawalApproved;
use App\Notifications\VendorStatusUpdated;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Notification;

// VendorStatusUpdated
it('sends VendorStatusUpdated approved notification via correct channels', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $user->notify(VendorStatusUpdated::approved());

    Notification::assertSentTo(
        $user,
        VendorStatusUpdated::class,
        function (VendorStatusUpdated $notification) use ($user): bool {
            return VendorStatus::Approved === $notification->status
                && in_array('broadcast', $notification->via($user), true)
                && in_array('fcm-device', $notification->via($user), true)
                && in_array('database', $notification->via($user), true);
        }
    );
});

it('sends VendorStatusUpdated rejected notification', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $user->notify(VendorStatusUpdated::rejected());

    Notification::assertSentTo(
        $user,
        VendorStatusUpdated::class,
        fn (VendorStatusUpdated $n) => VendorStatus::Rejected === $n->status
    );
});

it('VendorStatusUpdated broadcast payload contains status and message', function (): void {
    $user = User::factory()->create();
    $notification = VendorStatusUpdated::approved();

    $payload = $notification->toBroadcast($user);

    expect($payload['status'])->toBe(VendorStatus::Approved->value)
        ->and($payload['message'])->toContain('approved');
});

it('VendorStatusUpdated FCM notification has correct title for approval', function (): void {
    $user = User::factory()->create();
    $notification = VendorStatusUpdated::approved();

    $fcmMessage = $notification->toFcm($user);

    expect($fcmMessage)->toBeInstanceOf(CloudMessage::class);
});

// WithdrawalApproved
it('sends WithdrawalApproved notification via correct channels', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $transaction = Transaction::factory()->approved()->create(['user_id' => $user->getKey()]);

    $user->notify(new WithdrawalApproved($transaction));

    Notification::assertSentTo(
        $user,
        WithdrawalApproved::class,
        function (WithdrawalApproved $notification) use ($user, $transaction): bool {
            return $notification->transaction->getKey() === $transaction->getKey()
                && in_array('broadcast', $notification->via($user), true)
                && in_array('database', $notification->via($user), true);
        }
    );
});

it('WithdrawalApproved is not sent when status is not approved', function (): void {
    $user = User::factory()->create();
    $transaction = Transaction::factory()->create([
        'user_id' => $user->getKey(),
        'status' => TransactionStatus::Pending,
    ]);

    $notification = new WithdrawalApproved($transaction);

    expect($notification->shouldSend($user, 'broadcast'))->toBeFalse();
});

it('WithdrawalApproved is sent when status is approved', function (): void {
    $user = User::factory()->create();
    $transaction = Transaction::factory()->approved()->create(['user_id' => $user->getKey()]);

    $notification = new WithdrawalApproved($transaction);

    expect($notification->shouldSend($user, 'broadcast'))->toBeTrue();
});

it('WithdrawalApproved broadcast payload contains reference and amount', function (): void {
    $user = User::factory()->create();
    $transaction = Transaction::factory()->approved()->create([
        'user_id' => $user->getKey(),
        'amount' => '500.00',
    ]);

    $notification = new WithdrawalApproved($transaction);
    $payload = $notification->toBroadcast($user);

    expect($payload['reference'])->toBe($transaction->reference)
        ->and($payload['amount'])->toBe('500.00');
});
