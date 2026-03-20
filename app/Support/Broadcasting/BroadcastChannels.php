<?php

declare(strict_types=1);

namespace App\Support\Broadcasting;

use App\Models\User;
use App\Models\Livestream;

final class BroadcastChannels
{
    public const string LivestreamFeed = 'livestream_feed';

    public static function user(int|string|User $user): string
    {
        $userId = $user instanceof User ? $user->getKey() : $user;

        return "user_{$userId}";
    }

    public static function livestreamPresence(int|Livestream|string $livestream): string
    {
        $livestreamId = $livestream instanceof Livestream ? $livestream->getKey() : $livestream;

        return "livestream_{$livestreamId}";
    }
}
