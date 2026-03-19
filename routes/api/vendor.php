<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\TransactionController;
use App\Http\Controllers\Me\VendorOrderController;
use App\Http\Controllers\Me\ProductImageController;
use App\Http\Controllers\Me\SizeTemplateController;
use App\Http\Controllers\Me\ProductStatusController;
use App\Http\Controllers\Me\VendorProfileController;
use App\Http\Controllers\Me\PaymentAccountController;
use App\Http\Controllers\Me\VendorFollowerController;
use App\Http\Controllers\Me\SizeTemplateItemController;
use App\Http\Controllers\Me\LivestreamProductController;
use App\Http\Controllers\Me\ProductController as MeProductController;
use App\Http\Controllers\Me\LivestreamController as MeLivestreamController;
use App\Http\Controllers\Me\ShortVideoController as MeShortVideoController;

Route::middleware(['auth:sanctum'])
    ->prefix('me')
    ->group(function (): void {
        Route::prefix('vendors')->group(function (): void {
            Route::get('/', [VendorProfileController::class, 'show'])->name('me.vendors.show');
            Route::patch('/', [VendorProfileController::class, 'update'])->name('me.vendors.update');
            Route::get('followers', [VendorFollowerController::class, 'index'])->name('me.vendors.followers');
        });
        Route::get('balances', [VendorProfileController::class, 'balance'])->name('me.balances.index');

        Route::prefix('payment-accounts')->group(function (): void {
            Route::get('/', [PaymentAccountController::class, 'index'])->name('me.payment-accounts.index');
            Route::post('/', [PaymentAccountController::class, 'store'])->name('me.payment-accounts.store');
            Route::delete('{paymentAccount}', [
                PaymentAccountController::class,
                'destroy',
            ])->name('me.payment-accounts.destroy');
        });

        Route::prefix('products')->group(function (): void {
            Route::get('/', [MeProductController::class, 'index'])->name('me.products.index');
            Route::post('/', [MeProductController::class, 'store'])->name('me.products.store');
            Route::get('{product}', [MeProductController::class, 'show'])->name('me.products.show');
            Route::patch('{product}', [MeProductController::class, 'update'])->name('me.products.update');
            Route::delete('{product}', [MeProductController::class, 'destroy'])->name('me.products.destroy');
            Route::prefix('{product}')->group(function (): void {
                Route::singleton('status', ProductStatusController::class)->only('update');
            });
            Route::delete('{product}/images/{mediaId}', [ProductImageController::class, 'destroy'])->name('me.products.images.destroy');
        });

        Route::prefix('size-templates')->group(function (): void {
            Route::get('/', [SizeTemplateController::class, 'index'])->name('me.size-templates.index');
            Route::post('/', [SizeTemplateController::class, 'store'])->name('me.size-templates.store');
            Route::delete('{sizeTemplate}', [
                SizeTemplateController::class,
                'destroy',
            ])->name('me.size-templates.destroy');
        });

        Route::apiResource('size-templates.items', SizeTemplateItemController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['items' => 'sizeTemplateItem']);

        Route::prefix('livestreams')->group(function (): void {
            Route::get('/', [MeLivestreamController::class, 'index'])->name('me.livestreams.index');
            Route::post('/', [MeLivestreamController::class, 'store'])->name('me.livestreams.store');
            Route::patch('{livestream}', [
                MeLivestreamController::class,
                'update',
            ])->name('me.livestreams.update');
            Route::delete('{livestream}', [
                MeLivestreamController::class,
                'destroy',
            ])->name('me.livestreams.destroy');
            Route::get('{livestream}/publisher-token', [
                MeLivestreamController::class,
                'publisherToken',
            ])->name('me.livestreams.publisher-token');
        });

        Route::apiResource('livestreams.products', LivestreamProductController::class)
            ->only(['store', 'destroy']);

        Route::prefix('short-videos')->group(function (): void {
            Route::get('/', [MeShortVideoController::class, 'index'])->name('me.short-videos.index');
            Route::post('/', [MeShortVideoController::class, 'store'])->name('me.short-videos.store');
            Route::patch('{shortVideo}', [
                MeShortVideoController::class,
                'update',
            ])->name('me.short-videos.update');
            Route::delete('{shortVideo}', [
                MeShortVideoController::class,
                'destroy',
            ])->name('me.short-videos.destroy');
        });

        Route::prefix('vendor-orders')->group(function (): void {
            Route::get('/', [VendorOrderController::class, 'index'])->name('me.vendor-orders.index');
            Route::get('{vendorOrder}', [VendorOrderController::class, 'show'])->name('me.vendor-orders.show');
            Route::patch('{vendorOrder}/accept', [
                VendorOrderController::class,
                'accept',
            ])->name('me.vendor-orders.accept');
            Route::patch('{vendorOrder}/reject', [
                VendorOrderController::class,
                'reject',
            ])->name('me.vendor-orders.reject');
        });

        Route::get('transactions', [TransactionController::class, 'index'])->name('me.transactions.index');
        Route::post('withdrawals', [TransactionController::class, 'store'])->name('me.withdrawals.store');
    });

Route::middleware(['auth:sanctum'])
    ->prefix('vendor-applications')
    ->group(function (): void {
        Route::post('/', [VendorProfileController::class, 'apply'])->name('vendor-applications.store');
        Route::get('status', [
            VendorProfileController::class,
            'applicationStatus',
        ])->name('vendor-applications.status');
    });
