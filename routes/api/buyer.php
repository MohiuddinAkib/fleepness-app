<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\Me\ProfileController;

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
