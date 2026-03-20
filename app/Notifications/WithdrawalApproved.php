<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Transaction;
use App\Enums\BroadcastEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use App\Data\Broadcast\WithdrawalApprovedBroadcastData;

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
        return BroadcastEvent::WithdrawalRequestApproved->value;
    }

    /** @return array<string, mixed> */
    public function toBroadcast(object $notifiable): array
    {
        return new WithdrawalApprovedBroadcastData(
            reference: $this->transaction->reference,
            amount: (string) $this->transaction->amount,
        )->toArray();
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => BroadcastEvent::WithdrawalRequestApproved->value,
            'reference' => $this->transaction->reference,
            'amount' => (string) $this->transaction->amount,
        ];
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        return $this->transaction->status->shouldBroadcastWithdrawalApproval();
    }
}
