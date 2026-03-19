<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Notifications\DatabaseNotification;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group("Notifications", 'Manage the authenticated user\'s notifications.')]
class NotificationReadController extends Controller
{
    #[Authenticated]
    #[Endpoint("Mark all notifications as read")]
    #[Response('{"message":"Notifications marked as read."}', 200)]
    public function store(#[CurrentUser] User $user): JsonResponse
    {
        $user->unreadNotifications->markAsRead();

        return response()->json([
            "message" => "Notifications marked as read.",
        ]);
    }

    #[Authenticated]
    #[Endpoint("Mark a notification as read")]
    #[Response('{"message":"Notification marked as read."}', 200)]
    public function update(
        DatabaseNotification $notification,
        #[CurrentUser] User $user,
    ): JsonResponse {
        abort_if(
            $notification->notifiable()->isNot($user),
            HttpResponse::HTTP_NOT_FOUND,
        );

        if (null === $notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json([
            "message" => "Notification marked as read.",
        ]);
    }
}
