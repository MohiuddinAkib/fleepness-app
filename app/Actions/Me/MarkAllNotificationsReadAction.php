<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;

class MarkAllNotificationsReadAction
{
    public function execute(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }
}
