<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\Me\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CompatibilityController;

Route::middleware(['auth:sanctum'])
    ->prefix('me')
    ->group(function (): void {
        Route::get('/', [ProfileController::class, 'show']);
        Route::patch('/', [ProfileController::class, 'update']);

        Route::prefix('addresses')->group(function (): void {
            Route::get('/', [AddressController::class, 'index']);
            Route::post('/', [AddressController::class, 'store']);
            Route::patch('{address}', [AddressController::class, 'update']);
            Route::delete('{address}', [AddressController::class, 'destroy']);
            Route::post('{address}/default', [
                AddressController::class,
                'setDefault',
            ]);
        });

        Route::prefix('orders')->group(function (): void {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('{order}', [OrderController::class, 'show']);
        });

        Route::get('following', [FollowController::class, 'following']);
    });

Route::middleware(['auth:sanctum'])->group(function (): void {
    // Legacy aliases kept for the current React Native client.
    // Preferred modern replacements:
    // - `/api/me/role` -> `/api/me` and `/api/me/vendor`
    // - `/api/seller/status` -> `/api/vendor-application/status` or `/api/me/vendor`
    // - `/api/notifications*` -> move to future `/api/me/notifications*` endpoints
    // - `/api/addresses/default` -> `/api/me/addresses` and `is_default`
    // - `/api/followers` and `/api/following` -> keep follow state around vendor resources and future me-scoped endpoints
    Route::get('me/role', [CompatibilityController::class, 'role']);
    Route::get('seller/status', [CompatibilityController::class, 'sellerStatus']);
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/mark-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);

    Route::prefix('addresses')->group(function (): void {
        Route::get('default', [AddressController::class, 'default']);
        Route::post('{address}/set-default', [AddressController::class, 'setDefault']);
    });

    Route::get('following', [FollowController::class, 'following']);
    Route::get('followers', [FollowController::class, 'followers']);
});

Route::middleware(['auth:sanctum'])
    ->prefix('cart')
    ->group(function (): void {
        Route::get('/', [CartController::class, 'index']);
        Route::get('summary', [CartController::class, 'summary']);
        Route::post('items', [CartController::class, 'store']);
        Route::patch('items/{cartItem}', [CartController::class, 'update']);
        Route::delete('items/{cartItem}', [CartController::class, 'destroy']);
    });

Route::middleware(['auth:sanctum'])->post('orders', [
    OrderController::class,
    'store',
]);
