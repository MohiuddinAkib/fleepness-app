<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Legacy notification controller kept for response-shape compatibility.
 *
 * Preferred modern direction:
 * - keep notification reads grouped under authenticated "me" style endpoints when the client is migrated
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
     * Prefer a future `/api/me/notifications` style endpoint for new consumers so authenticated resources
     * stay grouped consistently with the rest of the API.
     */
    public function index(Request $request, #[CurrentUser] User $user)
    {
        $perPage = $request->integer('per_page', 15);

        $notifications = $user
            ->when('read' === $request->get('type'))
            ->readNotifications()
            ->when('unread' === $request->get('type'))
            ->unreadNotifications()
            ->latest()
            ->paginate(perPage: $perPage);

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'message' => 'Unread notifications retrieved successfully.',
        ]);
    }

    /**
     * Legacy alias for marking a single notification as read.
     *
     * Retained for `/api/notifications/{notification}/mark-as-read`. New consumers should prefer any
     * future me-scoped notification routes once the mobile client has migrated.
     */
    public function markAsRead(DatabaseNotification $notification, #[CurrentUser] User $user)
    {
        abort_if($notification->notifiable()->isNot($user), Response::HTTP_NOT_FOUND);

        $notification->markAsRead();

        return response()->json(['message' => 'Notifications marked as read']);
    }

    /**
     * Legacy bulk mark-as-read alias kept for the current mobile client contract.
     */
    public function markAllAsRead(#[CurrentUser] User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->notifications->markAsRead();

            return response()->json(['message' => 'Notifications marked as read']);
        });
    }
}
