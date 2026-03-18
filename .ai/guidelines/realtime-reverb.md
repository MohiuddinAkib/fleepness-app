# Real-Time Architecture — Laravel Reverb + FCM

## Problem Statement

The current setup uses the **FCM broadcaster as both the push notification driver and the real-time broadcast driver**. FCM is a push notification delivery system — it has inherent delivery latency and is not a WebSocket connection. Using it for in-app real-time updates (livestream comments, like counts, order status changes) causes lag that is unacceptable for a live commerce experience.

## Target Architecture — Dual Channel Strategy

Split responsibilities clearly:

| Responsibility | Technology | When Used |
|---|---|---|
| **In-app real-time** (user has app open, WebSocket alive) | **Laravel Reverb** | Active users, live sessions |
| **Offline / background push** (app closed or backgrounded) | **FCM** | User not connected to Reverb |

This is not an either/or — both must coexist. A user watching a livestream needs sub-100ms Reverb delivery. The same user closing the app still needs FCM push notifications.

---

## Reverb Setup Requirements

### Installation

```bash
php artisan install:broadcasting
```

This installs `laravel/reverb` and scaffolds the broadcasting config. Always check `php artisan install:broadcasting --help` first.

### Environment Configuration

```dotenv
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# Keep FCM config intact for push channel
FIREBASE_CREDENTIALS=storage/app/firebase/firebase_credentials.json
```

### Running Reverb

```bash
php artisan reverb:start
```

In production with Octane, Reverb runs as a separate long-running process. Do not run Reverb inside Octane workers.

---

## What Goes Through Reverb vs FCM

### Reverb (WebSocket — real-time, in-app only)

These events require WebSocket delivery because they update live UI:

| Event | Channel | Why Reverb |
|---|---|---|
| Livestream comments | `livestream_{id}` | Sub-second delivery required |
| Livestream like count changes | `livestream_{id}` | High-frequency counter update |
| Livestream created/updated | `livestream_feed` | Feed must update instantly |
| Order status changes | `user_{id}` | User is on the order screen |
| Seller order accepted/rejected | `user_{id}` | Vendor is on orders screen |
| Cart updates (multi-device sync) | `user_{id}` | User may have multiple devices |
| Seller withdrawal approved | `user_{id}` | In-app status update |

### FCM (Push Notification — offline/background only)

FCM is retained for notifying users who are **not actively connected via WebSocket**:

| Notification | Channel | Why FCM |
|---|---|---|
| New order received (to seller) | `fcm-device` | Seller may not have app open |
| Seller status approved/rejected | `fcm-device` | Background notification |
| OTP login | SMS only — no change | Unrelated to broadcasting |
| Withdrawal approved | `fcm-device` | Background alert |

---

## Broadcasting Channel Definitions

No changes needed to `routes/channels.php` — the existing channel authorization logic is correct. Reverb respects the same channel auth.

```php
// Already correct — no changes needed
Broadcast::channel('user_{id}', function (User $user, $id) {
    return (int) $user->getKey() === (int) $id;
}, ['guards' => ['sanctum']]);

Broadcast::channel('livestream_feed', fn () => true);

Broadcast::channel('livestream_{livestream}', function (?User $user, Livestream $livestream) {
    return LivestreamStatuses::STARTED === $livestream->status;
}, ['guards' => ['sanctum']]);
```

---

## Notification Class Pattern — Dual Channel

For events that need both real-time and push (e.g., order status), the notification must support both:

```php
class OrderStatusChanged extends Notification implements ShouldBroadcast, ShouldQueue
{
    public function via(object $notifiable): array
    {
        // Reverb handles in-app; FCM handles background push
        return ['broadcast', 'fcm-device', 'database'];
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel("user_{$this->order->user->getKey()}")];
    }

    public function broadcastAs(): string
    {
        return 'order_status_changed';
    }

    public function broadcastWith(): array
    {
        return (new OrderResource($this->order))->resolve();
    }

    // FCM push payload for background delivery
    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::new()
            ->withNotification(Notification::create('Order Update', "Your order status has changed."))
            ->withData(['event' => 'order_status_changed', 'order_id' => (string) $this->order->getKey()]);
    }

    public function toFcmTokens(object $notifiable): array
    {
        return $notifiable->deviceTokens->pluck('token')->all();
    }
}
```

### Key Rules for Dual-Channel Notifications

- Always include `'broadcast'` in `via()` for Reverb delivery.
- Include `'fcm-device'` in `via()` only when a push notification is also needed.
- `broadcastWith()` must return a typed array from an API Resource — never raw model attributes.
- The FCM `toFcm()` payload should be minimal — just enough to open the right screen.
- Use `ShouldQueueAfterCommit` instead of `ShouldQueue` when the notification depends on a DB write completing first.

---

## Presence Channels for Livestreams

Livestream viewer count and participant lists should use a **presence channel**, not a plain channel. This allows clients to know who is watching.

```php
Broadcast::channel('livestream_{livestream}', function (User $user, Livestream $livestream) {
    if (LivestreamStatuses::STARTED !== $livestream->status) {
        return false;
    }
    return [
        'id' => $user->getKey(),
        'name' => $user->name,
    ];
}, ['guards' => ['sanctum']]);
```

Client subscribes to `presence-livestream_{id}`. Reverb automatically tracks member join/leave events.

---

## Existing FCM Broadcaster — Do Not Remove

The custom `FcmBroadcaster` in `app/Support/Broadcaster/FcmBroadcaster.php` must be **retained** as the `fcm` driver for the broadcast manager. It is still used by notifications that go through `via(['broadcast'])` when the broadcast connection is `fcm` — but with Reverb as the default connection, it becomes a secondary driver used only for topic-based push broadcasting if needed.

Register both drivers:

```php
// AppServiceProvider — already exists, keep as-is
Broadcast::resolved(function (BroadcastManager $service): void {
    $service->extend('fcm', fn (Application $app, array $config) => $app->make(FcmBroadcaster::class));
});
```

---

## Deployment Notes

- Reverb must run as a separate supervised process (Supervisor, `php artisan reverb:start`).
- Do not run Reverb behind the same Octane server instance.
- Reverb requires a persistent TCP connection — ensure load balancers are configured for WebSocket (sticky sessions or WebSocket-aware proxy).
- Set `REVERB_HOST` to the public hostname in production, not `127.0.0.1`.
- For horizontal scaling, Reverb supports Redis as a pub/sub backend — configure when deploying multiple Reverb nodes.
