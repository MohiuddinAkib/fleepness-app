<?php

use App\Models\User;
use App\Models\Livestream;
use App\Constants\LivestreamStatuses;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user_{id}', function (User $user, $id) { // selller or buyer will get their personal notifications here
    return (int) $user->getKey() === (int) $id;
}, ['guards' => ['sanctum']]);

// ! MUST COME BEFORE livestream_{livestream}
Broadcast::channel('livestream_feed', function () {
    return true;
});

// Presence channel — Reverb tracks member join/leave automatically, enabling live viewer counts
Broadcast::channel('presence-livestream_{livestream}', function (User $user, Livestream $livestream) {
    if (LivestreamStatuses::STARTED !== $livestream->status) {
        return false;
    }

    return [
        'id' => $user->getKey(),
        'name' => $user->name,
    ];
}, ['guards' => ['sanctum']]);
