<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class MarkNotificationReadAction
{
    public function execute(User $user, DatabaseNotification $notification): void
    {
        abort_if($notification->notifiable()->isNot($user), HttpResponse::HTTP_NOT_FOUND);

        if ($notification->unread()) {
            $notification->markAsRead();
        }
    }
}
