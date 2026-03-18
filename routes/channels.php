<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Livestream;
use App\Enums\LivestreamStatus;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('user_{id}', function (User $user, int $id): bool {
    return $user->getKey() === $id;
}, ['guards' => ['sanctum']]);

// Must be declared BEFORE the presence channel to avoid route ambiguity
Broadcast::channel('livestream_feed', fn (): bool => true);

// Presence channel — returns member data so Reverb tracks who is watching
Broadcast::channel('presence-livestream_{livestream}', function (User $user, Livestream $livestream): array|false {
    if (LivestreamStatus::Started !== $livestream->status) {
        return false;
    }

    return [
        'id' => $user->getKey(),
        'name' => $user->name,
    ];
}, ['guards' => ['sanctum']]);
