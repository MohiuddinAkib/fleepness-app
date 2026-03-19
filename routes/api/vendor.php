<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\TransactionController;
use App\Http\Controllers\Me\VendorOrderController;
use App\Http\Controllers\Me\SizeTemplateController;
use App\Http\Controllers\Me\ProductStatusController;
use App\Http\Controllers\Me\VendorProfileController;
use App\Http\Controllers\Me\PaymentAccountController;
use App\Http\Controllers\Me\ProductController as MeProductController;
use App\Http\Controllers\Me\LivestreamController as MeLivestreamController;
use App\Http\Controllers\Me\ShortVideoController as MeShortVideoController;

Route::middleware(['auth:sanctum'])
    ->prefix('me')
    ->group(function (): void {
        Route::prefix('vendor')->group(function (): void {
            Route::get('/', [VendorProfileController::class, 'show']);
            Route::patch('/', [VendorProfileController::class, 'update']);
        });
        Route::get('balance', [VendorProfileController::class, 'balance']);

        Route::prefix('payment-accounts')->group(function (): void {
            Route::get('/', [PaymentAccountController::class, 'index']);
            Route::post('/', [PaymentAccountController::class, 'store']);
            Route::delete('{paymentAccount}', [
                PaymentAccountController::class,
                'destroy',
            ]);
        });

        Route::prefix('products')->group(function (): void {
            Route::get('/', [MeProductController::class, 'index']);
            Route::post('/', [MeProductController::class, 'store']);
            Route::get('{product}', [MeProductController::class, 'show']);
            Route::patch('{product}', [MeProductController::class, 'update']);
            Route::delete('{product}', [MeProductController::class, 'destroy']);
            Route::prefix('{product}')->group(function (): void {
                Route::singleton('status', ProductStatusController::class)->only('update');
            });
            Route::delete('{product}/images/{mediaId}', [
                MeProductController::class,
                'destroyImage',
            ]);
        });

        Route::prefix('size-templates')->group(function (): void {
            Route::get('/', [SizeTemplateController::class, 'index']);
            Route::post('/', [SizeTemplateController::class, 'store']);
            Route::delete('{sizeTemplate}', [
                SizeTemplateController::class,
                'destroy',
            ]);
            Route::post('{sizeTemplate}/items', [
                SizeTemplateController::class,
                'storeItem',
            ]);
            Route::patch('{sizeTemplate}/items/{sizeTemplateItem}', [
                SizeTemplateController::class,
                'updateItem',
            ]);
            Route::delete('{sizeTemplate}/items/{sizeTemplateItem}', [
                SizeTemplateController::class,
                'destroyItem',
            ]);
        });

        Route::prefix('livestreams')->group(function (): void {
            Route::get('/', [MeLivestreamController::class, 'index']);
            Route::post('/', [MeLivestreamController::class, 'store']);
            Route::patch('{livestream}', [
                MeLivestreamController::class,
                'update',
            ]);
            Route::delete('{livestream}', [
                MeLivestreamController::class,
                'destroy',
            ]);
            Route::get('{livestream}/publisher-token', [
                MeLivestreamController::class,
                'publisherToken',
            ]);
            Route::post('{livestream}/products', [
                MeLivestreamController::class,
                'attachProduct',
            ]);
            Route::delete('{livestream}/products/{product}', [
                MeLivestreamController::class,
                'detachProduct',
            ]);
        });

        Route::prefix('short-videos')->group(function (): void {
            Route::get('/', [MeShortVideoController::class, 'index']);
            Route::post('/', [MeShortVideoController::class, 'store']);
            Route::patch('{shortVideo}', [
                MeShortVideoController::class,
                'update',
            ]);
            Route::delete('{shortVideo}', [
                MeShortVideoController::class,
                'destroy',
            ]);
        });

        Route::prefix('vendor-orders')->group(function (): void {
            Route::get('/', [VendorOrderController::class, 'index']);
            Route::get('{vendorOrder}', [VendorOrderController::class, 'show']);
            Route::patch('{vendorOrder}/accept', [
                VendorOrderController::class,
                'accept',
            ]);
            Route::patch('{vendorOrder}/reject', [
                VendorOrderController::class,
                'reject',
            ]);
        });

        Route::get('transactions', [TransactionController::class, 'index']);
        Route::post('withdrawals', [TransactionController::class, 'store']);
    });

Route::middleware(['auth:sanctum'])
    ->prefix('vendor-application')
    ->group(function (): void {
        Route::post('/', [VendorProfileController::class, 'apply']);
        Route::get('status', [
            VendorProfileController::class,
            'applicationStatus',
        ]);
    });
