<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\VendorOrder;
use App\Data\VendorOrderData;
use Illuminate\Bus\Queueable;
use App\Enums\VendorOrderStatus;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

/**
 * Sent to the customer when their vendor order status changes.
 * Channel: broadcast (Reverb) → user_{customer_id}, database.
 */
class VendorOrderStatusChanged extends Notification implements ShouldBroadcast, ShouldQueueAfterCommit
{
    use Queueable;

    public function __construct(public readonly VendorOrder $vendorOrder) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function broadcastAs(): string
    {
        return 'vendor_order_status_changed';
    }

    /** @return array<string, mixed> */
    public function toBroadcast(object $notifiable): array
    {
        return [
            'vendor_order' => VendorOrderData::fromModel($this->vendorOrder)->toArray(),
        ];
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'vendor_order_status_changed',
            'vendor_order_id' => $this->vendorOrder->getKey(),
            'order_number' => $this->vendorOrder->order_number,
            'status' => $this->vendorOrder->status->value,
        ];
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        return VendorOrderStatus::Pending !== $this->vendorOrder->status;
    }
}
