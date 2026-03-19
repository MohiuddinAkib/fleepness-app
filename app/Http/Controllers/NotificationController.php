<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use App\Data\Me\ListNotificationsData;
use App\Actions\Me\ListNotificationsAction;
use App\Actions\Me\MarkNotificationReadAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Me\MarkAllNotificationsReadAction;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Legacy notification controller kept for response-shape compatibility.
 *
 * Preferred modern direction:
 * - use `/api/me/notifications`
 * - use `/api/me/notifications/read`
 * - use `/api/me/notifications/{notification}/read`
 * - use the structured broadcast/database notification payloads already emitted by the notification classes
 *
 * The React Native client still expects `/api/notifications` and the mark-as-read aliases below, so these
 * endpoints remain stable for now. New API work should not expand this controller unless it is also needed
 * for legacy-client compatibility.
 */
class NotificationController extends Controller
{
    /**
     * Legacy notifications listing endpoint used by the mobile client.
     *
     * Prefer `/api/me/notifications` for new consumers so authenticated resources stay grouped
     * consistently with the rest of the API.
     */
    public function index(
        ListNotificationsData $data,
        #[CurrentUser] User $user,
        ListNotificationsAction $listNotifications,
    ) {
        $notifications = $listNotifications->execute($user, $data);

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'message' => 'Unread notifications retrieved successfully.',
        ]);
    }

    /**
     * Legacy alias for marking a single notification as read.
     *
     * Retained for `/api/notifications/{notification}/mark-as-read`. New consumers should prefer
     * `/api/me/notifications/{notification}/read`.
     */
    public function markAsRead(
        DatabaseNotification $notification,
        #[CurrentUser] User $user,
        MarkNotificationReadAction $markNotificationRead,
    ) {
        $markNotificationRead->execute($user, $notification);

        return response()->json(['message' => 'Notifications marked as read']);
    }

    /**
     * Legacy bulk mark-as-read alias kept for the current mobile client contract.
     *
     * New consumers should prefer `/api/me/notifications/read`.
     */
    public function markAllAsRead(
        #[CurrentUser] User $user,
        MarkAllNotificationsReadAction $markAllNotificationsRead,
    ) {
        $markAllNotificationsRead->execute($user);

        return response()->json(['message' => 'Notifications marked as read']);
    }
}
