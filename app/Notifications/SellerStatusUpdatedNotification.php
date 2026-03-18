<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\SellerStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use App\Support\Notification\Contracts\SupportsSmsChannel;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use App\Support\Notification\Contracts\SupportsFcmDeviceChannel;

class SellerStatusUpdatedNotification extends Notification implements ShouldBroadcast, ShouldQueueAfterCommit, SupportsFcmDeviceChannel, SupportsSmsChannel
{
    use Queueable;

    public function __construct(public readonly SellerStatus $status) {}

    public static function approved()
    {
        return new self(SellerStatus::Approved);
    }

    public static function rejected()
    {
        return new self(SellerStatus::Rejected);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['sms', 'broadcast', 'fcm-device'];
    }

    public function toSms(object $notifiable): string
    {
        return $this->status->messageBody();
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toBroadcast(object $notifiable)
    {
        return [
            'notifiable' => $notifiable,
            'notification' => [
                'title' => $this->status->messageTitle(),
                'body' => $this->status->messageBody(),
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'seller_status_updated';
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::new()
            ->withNotification(FcmNotification::create(
                $this->status->messageTitle(),
                $this->status->messageBody(),
            ));
    }

    public function toFcmTokens(object $notifiable): null|array|string
    {
        return $notifiable->routeNotificationForFcmTokens($this);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->status->messageTitle(),
            'body' => $this->status->messageBody(),
        ];
    }
}
