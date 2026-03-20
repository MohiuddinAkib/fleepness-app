<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\VendorOrder;
use App\Data\VendorOrderData;
use App\Enums\BroadcastEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Data\Broadcast\VendorOrderBroadcastData;
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
        return BroadcastEvent::CustomerOrderStatusChanged->value;
    }

    /** @return array<string, mixed> */
    public function toBroadcast(object $notifiable): array
    {
        return new VendorOrderBroadcastData(
            vendorOrder: VendorOrderData::fromModel($this->vendorOrder->loadMissing('items.product', 'vendorProfile')),
        )->toArray();
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => BroadcastEvent::CustomerOrderStatusChanged->value,
            'vendor_order_id' => $this->vendorOrder->getKey(),
            'order_number' => $this->vendorOrder->order_number,
            'status' => $this->vendorOrder->status->value,
        ];
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        return $this->vendorOrder->status->shouldBroadcastCustomerUpdate();
    }
}
