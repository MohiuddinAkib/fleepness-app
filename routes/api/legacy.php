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
    // - `/api/me/role` -> `/api/v1/me/summaries` and `/api/v1/me/vendors`
    // - `/api/seller/status` -> `/api/v1/me/summaries`, `/api/v1/vendor-applications/status`, or `/api/v1/me/vendors`
    // - `/api/notifications*` -> `/api/v1/me/notifications*`
    // - `/api/addresses/default` -> `/api/v1/me/addresses` and `is_default`
    // - `/api/followers` -> `/api/v1/me/vendors/followers`
    // - `/api/following` -> `/api/v1/me/followings`
    // - `/api/user/balance-stats` -> `/api/v1/me/balances`
    Route::get('me/role', [CompatibilityController::class, 'role'])->middleware('legacy-endpoint:me.role');
    Route::get('seller/status', [CompatibilityController::class, 'sellerStatus'])->middleware('legacy-endpoint:seller.status');
    Route::get('notifications', [NotificationController::class, 'index'])->middleware('legacy-endpoint:notifications.index');
    Route::post('notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->middleware('legacy-endpoint:notifications.mark-all-as-read');
    Route::post('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->middleware('legacy-endpoint:notifications.mark-as-read');

    Route::prefix('addresses')->group(function (): void {
        Route::get('default', [AddressController::class, 'default'])->middleware('legacy-endpoint:addresses.default');
        Route::post('{address}/set-default', [AddressController::class, 'setDefault'])->middleware('legacy-endpoint:addresses.set-default');
    });

    Route::get('following', [FollowController::class, 'followings'])->middleware('legacy-endpoint:following.index');
    Route::get('followers', [FollowController::class, 'followers'])->middleware('legacy-endpoint:followers.index');
    Route::get('user/balance-stats', [CompatibilityController::class, 'balanceStats'])->middleware('legacy-endpoint:user.balance-stats');

    // Legacy alias retained for the current mobile client.
    // Preferred modern direction: use `/api/v1/me/short-videos/saved` for the authenticated saved collection
    // and `/api/v1/short-videos` for the canonical public browsing collection.
    Route::get('shorts/saved', [ShortVideoController::class, 'saved'])->middleware('legacy-endpoint:shorts.saved');

    // Legacy `/api/lives/*` aliases retained for the current mobile client.
    // Preferred modern direction: use `/api/v1/me/livestreams/{liked,saved}` for authenticated collections,
    // `/api/v1/livestreams` for the canonical public collection, and realtime updates for counters.
    Route::prefix('lives')->group(function (): void {
        Route::get('liked', [LivestreamController::class, 'liked'])->middleware('legacy-endpoint:lives.liked');
        Route::get('saved', [LivestreamController::class, 'saved'])->middleware('legacy-endpoint:lives.saved');
        Route::get('{livestream}/likes-count', [
            LivestreamController::class,
            'likesCount',
        ])->middleware('legacy-endpoint:lives.likes-count');
    });
});
