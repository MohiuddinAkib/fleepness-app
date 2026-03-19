<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\Me\ProfileController;
use App\Http\Controllers\Me\NotificationController;
use App\Http\Controllers\Me\AccountSummaryController;
use App\Http\Controllers\Me\LikedLivestreamController;
use App\Http\Controllers\Me\SavedLivestreamController;
use App\Http\Controllers\Me\SavedShortVideoController;
use App\Http\Controllers\Me\NotificationReadController;

Route::middleware(['auth:sanctum'])
    ->prefix('me')
    ->group(function (): void {
        Route::get('/', [ProfileController::class, 'show'])->name('me.profile.show');
        Route::patch('/', [ProfileController::class, 'update'])->name('me.profile.update');
        Route::get('summaries', [AccountSummaryController::class, 'index'])->name('me.summaries.index');

        Route::prefix('notifications')->group(function (): void {
            Route::get('/', [NotificationController::class, 'index'])->name('me.notifications.index');
            Route::post('read', [NotificationReadController::class, 'store'])->name('me.notifications.read-all');
            Route::post('{notification}/read', [
                NotificationReadController::class,
                'update',
            ])->name('me.notifications.read-one');
        });

        Route::prefix('addresses')->group(function (): void {
            Route::get('/', [AddressController::class, 'index'])->name('me.addresses.index');
            Route::post('/', [AddressController::class, 'store'])->name('me.addresses.store');
            Route::patch('{address}', [AddressController::class, 'update'])->name('me.addresses.update');
            Route::delete('{address}', [AddressController::class, 'destroy'])->name('me.addresses.destroy');
            Route::post('{address}/default', [
                AddressController::class,
                'setDefault',
            ])->name('me.addresses.set-default');
        });

        Route::prefix('orders')->group(function (): void {
            Route::get('/', [OrderController::class, 'index'])->name('me.orders.index');
            Route::get('{order}', [OrderController::class, 'show'])->name('me.orders.show');
        });

        Route::get('followings', [FollowController::class, 'followings'])->name('me.followings.index');
        Route::prefix('short-videos')->group(function (): void {
            Route::get('saved', [SavedShortVideoController::class, 'index'])->name('me.short-videos.saved');
        });

        Route::prefix('livestreams')->group(function (): void {
            Route::get('liked', [LikedLivestreamController::class, 'index'])->name('me.livestreams.liked');
            Route::get('saved', [SavedLivestreamController::class, 'index'])->name('me.livestreams.saved');
        });
    });

Route::middleware(['auth:sanctum'])
    ->prefix('cart')
    ->group(function (): void {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::get('summary', [CartController::class, 'summary'])->name('cart.summary');
        Route::post('items', [CartController::class, 'store'])->name('cart.items.store');
        Route::patch('items/{cartItem}', [CartController::class, 'update'])->name('cart.items.update');
        Route::delete('items/{cartItem}', [CartController::class, 'destroy'])->name('cart.items.destroy');
    });

Route::middleware(['auth:sanctum'])->post('orders', [
    OrderController::class,
    'store',
])->name('orders.store');
