<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Livestream;
use App\Enums\LivestreamStatus;
use Illuminate\Support\Facades\Broadcast;
use App\Support\Broadcasting\BroadcastChannels;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| user_{id}              — Private channel for a specific user (orders,
|                          notifications, balance updates).
| livestream_feed        — Public channel for the livestream listing feed.
| presence-livestream_{livestream} — Presence channel for an active livestream
|                          room; Reverb tracks member join/leave for viewer counts.
|
*/

Broadcast::channel(BroadcastChannels::user('{id}'), function (User $user, int $id): bool {
    return $user->getKey() === $id;
}, ['guards' => ['sanctum']]);

// Must be declared BEFORE the presence channel to avoid route ambiguity
Broadcast::channel(BroadcastChannels::LivestreamFeed, fn (): bool => true);

// Presence channel — returns member data so Reverb tracks who is watching
Broadcast::channel('presence-'.BroadcastChannels::livestreamPresence('{livestream}'), function (User $user, Livestream $livestream): array|false {
    if (LivestreamStatus::Started !== $livestream->status) {
        return false;
    }

    return [
        'id' => $user->getKey(),
        'name' => $user->name,
    ];
}, ['guards' => ['sanctum']]);
