<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\VendorStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use App\Support\Notification\Contracts\SupportsFcmDeviceChannel;

/**
 * Sent to the vendor's user when their application status is approved or rejected.
 * Channel: broadcast (Reverb) → user_{id}, fcm-device (FCM push), database.
 */
class VendorStatusUpdated extends Notification implements ShouldBroadcast, ShouldQueueAfterCommit, SupportsFcmDeviceChannel
{
    use Queueable;

    public function __construct(public readonly VendorStatus $status) {}

    public static function approved(): self
    {
        return new self(VendorStatus::Approved);
    }

    public static function rejected(): self
    {
        return new self(VendorStatus::Rejected);
    }

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'fcm-device', 'database'];
    }

    public function broadcastAs(): string
    {
        return 'vendor_status_updated';
    }

    /** @return array<string, mixed> */
    public function toBroadcast(object $notifiable): array
    {
        return [
            'status' => $this->status->value,
            'message' => $this->message(),
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::new()->withNotification(
            FcmNotification::create('Vendor Application Update', $this->message())
        )->withData([
            'type' => 'vendor_status_updated',
            'status' => $this->status->value,
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
            'type' => 'vendor_status_updated',
            'status' => $this->status->value,
            'message' => $this->message(),
        ];
    }

    private function message(): string
    {
        return match ($this->status) {
            VendorStatus::Approved => 'Congratulations! Your vendor application has been approved.',
            VendorStatus::Rejected => 'Your vendor application has been rejected. Please contact support.',
            default => 'Your vendor application status has been updated.',
        };
    }
}
