<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\VendorOrder;
use App\Data\VendorOrderData;
use App\Enums\BroadcastEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Data\Broadcast\VendorOrderBroadcastData;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use App\Support\Notification\Contracts\SupportsFcmDeviceChannel;

/**
 * Sent to the vendor (user) when a new order arrives.
 * Channel: broadcast (Reverb) → user_{id}, fcm-device (FCM push), database.
 */
class OrderReceivedByVendor extends Notification implements ShouldBroadcast, ShouldQueueAfterCommit, SupportsFcmDeviceChannel
{
    use Queueable;

    public function __construct(public readonly VendorOrder $vendorOrder) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'fcm-device', 'database'];
    }

    public function broadcastAs(): string
    {
        return BroadcastEvent::NewOrderForVendor->value;
    }

    /** @return array<string, mixed> */
    public function toBroadcast(object $notifiable): array
    {
        return new VendorOrderBroadcastData(
            vendorOrder: VendorOrderData::fromModel($this->vendorOrder->loadMissing('items.product', 'vendorProfile')),
        )->toArray();
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::new()->withNotification(
            FcmNotification::create(
                'New Order Received',
                "Order #{$this->vendorOrder->order_number} is waiting for your confirmation."
            )
        )->withData([
            'type' => BroadcastEvent::NewOrderForVendor->value,
            'vendor_order_id' => (string) $this->vendorOrder->getKey(),
        ]);
    }

    /** @return list<string> */
    public function toFcmTokens(object $notifiable): array
    {
        return $notifiable->routeNotificationForFcmTokens($this);
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => BroadcastEvent::NewOrderForVendor->value,
            'vendor_order_id' => $this->vendorOrder->getKey(),
            'order_number' => $this->vendorOrder->order_number,
        ];
    }
}
