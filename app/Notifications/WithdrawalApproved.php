<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

/**
 * Sent to the user when their withdrawal transaction is approved.
 * Channel: broadcast (Reverb) → user_{id}, database.
 */
class WithdrawalApproved extends Notification implements ShouldBroadcast, ShouldQueueAfterCommit
{
    use Queueable;

    public function __construct(public readonly Transaction $transaction) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function broadcastAs(): string
    {
        return 'withdrawal_request_approved';
    }

    /** @return array<string, mixed> */
    public function toBroadcast(object $notifiable): array
    {
        return [
            'reference' => $this->transaction->reference,
            'amount' => (string) $this->transaction->amount,
        ];
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'withdrawal_request_approved',
            'reference' => $this->transaction->reference,
            'amount' => (string) $this->transaction->amount,
        ];
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        return $this->transaction->status->shouldBroadcastWithdrawalApproval();
    }
}
