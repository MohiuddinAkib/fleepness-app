<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\Me\ProfileController;
use App\Http\Controllers\Me\NotificationController;
use App\Http\Controllers\Me\LikedLivestreamController;
use App\Http\Controllers\Me\SavedLivestreamController;
use App\Http\Controllers\Me\SavedShortVideoController;
use App\Http\Controllers\Me\NotificationReadController;

Route::middleware(['auth:sanctum'])
    ->prefix('me')
    ->group(function (): void {
        Route::get('/', [ProfileController::class, 'show']);
        Route::patch('/', [ProfileController::class, 'update']);

        Route::prefix('notifications')->group(function (): void {
            Route::get('/', [NotificationController::class, 'index']);
            Route::post('read', [NotificationReadController::class, 'store']);
            Route::post('{notification}/read', [
                NotificationReadController::class,
                'update',
            ]);
        });

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

        Route::prefix('short-videos')->group(function (): void {
            Route::get('saved', [SavedShortVideoController::class, 'index']);
        });

        Route::prefix('livestreams')->group(function (): void {
            Route::get('liked', [LikedLivestreamController::class, 'index']);
            Route::get('saved', [SavedLivestreamController::class, 'index']);
        });
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
