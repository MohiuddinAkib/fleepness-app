<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\NotificationData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use App\Data\Me\ListNotificationsData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\QueryParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Notifications', 'Manage the authenticated user\'s notifications.')]
class NotificationController extends Controller
{
    #[Authenticated]
    #[Endpoint('List notifications')]
    #[QueryParam('per_page', 'integer', required: false, example: 15)]
    #[QueryParam('type', 'string', required: false, example: 'unread')]
    #[Response('{"data":[{"id":"uuid","type":"legacy.test","data":{"message":"Hello"}}],"meta":{"current_page":1}}', 200)]
    public function index(
        ListNotificationsData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $notifications = match ($data->type) {
            'read' => $user->readNotifications(),
            'unread' => $user->unreadNotifications(),
            default => $user->notifications(),
        };

        return NotificationData::collect(
            $notifications->latest()->paginate($data->perPage),
            PaginatedDataCollection::class
        );
    }
}
