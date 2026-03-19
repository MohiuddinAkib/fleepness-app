<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;
use App\Data\Me\ListNotificationsData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;

class ListNotificationsAction
{
    /**
     * @return LengthAwarePaginator<int, DatabaseNotification>
     */
    public function execute(User $user, ListNotificationsData $data): LengthAwarePaginator
    {
        $notifications = match ($data->type) {
            'read' => $user->readNotifications(),
            'unread' => $user->unreadNotifications(),
            default => $user->notifications(),
        };

        /** @var LengthAwarePaginator<int, DatabaseNotification> $paginator */
        $paginator = $notifications->latest()->paginate($data->perPage);

        return $paginator;
    }
}
