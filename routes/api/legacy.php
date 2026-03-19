<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CompatibilityController;
use App\Http\Controllers\Public\LivestreamController;
use App\Http\Controllers\Public\ShortVideoController;

Route::middleware(['auth:sanctum'])->group(function (): void {
    // Legacy aliases kept for the current React Native client.
    // Preferred modern replacements:
    // - `/api/me/role` -> `/api/me` and `/api/me/vendor`
    // - `/api/seller/status` -> `/api/vendor-application/status` or `/api/me/vendor`
    // - `/api/notifications*` -> move to future `/api/me/notifications*` endpoints
    // - `/api/addresses/default` -> `/api/me/addresses` and `is_default`
    // - `/api/followers` and `/api/following` -> keep follow state around vendor resources and future me-scoped endpoints
    // - `/api/user/balance-stats` -> `/api/me/balance`
    Route::get('me/role', [CompatibilityController::class, 'role'])->middleware('legacy-endpoint:me.role');
    Route::get('seller/status', [CompatibilityController::class, 'sellerStatus'])->middleware('legacy-endpoint:seller.status');
    Route::get('notifications', [NotificationController::class, 'index'])->middleware('legacy-endpoint:notifications.index');
    Route::post('notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->middleware('legacy-endpoint:notifications.mark-all-as-read');
    Route::post('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->middleware('legacy-endpoint:notifications.mark-as-read');

    Route::prefix('addresses')->group(function (): void {
        Route::get('default', [AddressController::class, 'default'])->middleware('legacy-endpoint:addresses.default');
        Route::post('{address}/set-default', [AddressController::class, 'setDefault'])->middleware('legacy-endpoint:addresses.set-default');
    });

    Route::get('following', [FollowController::class, 'following'])->middleware('legacy-endpoint:following.index');
    Route::get('followers', [FollowController::class, 'followers'])->middleware('legacy-endpoint:followers.index');
    Route::get('user/balance-stats', [CompatibilityController::class, 'balanceStats'])->middleware('legacy-endpoint:user.balance-stats');

    // Legacy alias retained for the current mobile client.
    // Preferred modern direction: use `/api/short-videos` as the canonical collection and migrate any
    // saved-content view to a future me-scoped endpoint instead of extending the historical `/api/shorts/*` routes.
    Route::get('shorts/saved', [ShortVideoController::class, 'saved'])->middleware('legacy-endpoint:shorts.saved');

    // Legacy `/api/lives/*` aliases retained for the current mobile client.
    // Preferred modern direction: use `/api/livestreams` as the canonical collection/resource and lean on
    // realtime updates for counters instead of polling these historical endpoints.
    Route::prefix('lives')->group(function (): void {
        Route::get('liked', [LivestreamController::class, 'liked'])->middleware('legacy-endpoint:lives.liked');
        Route::get('saved', [LivestreamController::class, 'saved'])->middleware('legacy-endpoint:lives.saved');
        Route::get('{livestream}/likes-count', [
            LivestreamController::class,
            'likesCount',
        ])->middleware('legacy-endpoint:lives.likes-count');
    });
});
