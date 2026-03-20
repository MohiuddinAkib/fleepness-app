<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Data\Response\MessageResponseData;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Actions\Me\MarkNotificationReadAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Me\MarkAllNotificationsReadAction;
use Illuminate\Notifications\DatabaseNotification;

#[Group('Notifications', 'Manage the authenticated user\'s notifications.')]
class NotificationReadController extends Controller
{
    #[Authenticated]
    #[Endpoint('Mark all notifications as read')]
    #[Response('{"message":"Notifications marked as read."}', 200)]
    /** @return MessageResponseData */
    public function store(
        #[CurrentUser] User $user,
        MarkAllNotificationsReadAction $markAllNotificationsRead,
    ): JsonResponse|MessageResponseData {
        $markAllNotificationsRead->execute($user);

        return response()->json(MessageResponseData::from([
            'message' => 'Notifications marked as read.',
        ])->toArray());
    }

    #[Authenticated]
    #[Endpoint('Mark a notification as read')]
    #[Response('{"message":"Notification marked as read."}', 200)]
    /** @return MessageResponseData */
    public function update(
        DatabaseNotification $notification,
        #[CurrentUser] User $user,
        MarkNotificationReadAction $markNotificationRead,
    ): JsonResponse|MessageResponseData {
        $markNotificationRead->execute($user, $notification);

        return response()->json(MessageResponseData::from([
            'message' => 'Notification marked as read.',
        ])->toArray());
    }
}
