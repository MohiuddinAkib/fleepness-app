<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\TagController;
use App\Http\Controllers\Auth\OTPAuthController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\VendorController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\SectionController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Auth\DeviceTokenController;
use App\Http\Controllers\Public\LivestreamController;
use App\Http\Controllers\Public\ShortVideoController;
use App\Http\Controllers\Public\ShopCategoryController;
use App\Http\Controllers\Public\VendorReviewController;
use App\Http\Controllers\Public\PaymentMethodController;
use App\Http\Controllers\Public\ProductReviewController;
use App\Http\Controllers\Public\DeliveryOptionController;
use App\Http\Controllers\Public\LivestreamCommentController;
use App\Http\Controllers\Public\ShortVideoCommentController;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [OTPAuthController::class, 'register'])->name('auth.register');
    Route::post('verify-otp', [OTPAuthController::class, 'verifyOtp'])->name('auth.verify-otp');
    Route::post('resend-otp', [OTPAuthController::class, 'resendOtp'])->name('auth.resend-otp');
    Route::post('login', [OTPAuthController::class, 'login'])->name('auth.login');

    Route::prefix('social')->group(function (): void {
        Route::get('{provider}', [SocialAuthController::class, 'redirect'])->name('auth.social.redirect');
        Route::get('{provider}/callback', [
            SocialAuthController::class,
            'callback',
        ])->name('auth.social.callback');
    });

    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('logout', [SessionController::class, 'destroy'])->name('auth.logout');
        Route::post('device-tokens', [DeviceTokenController::class, 'store'])->name('auth.device-tokens.store');
        Route::delete('device-tokens/{deviceToken}', [
            DeviceTokenController::class,
            'destroy',
        ])->name('auth.device-tokens.destroy');
    });
});

require __DIR__.'/buyer.php';
require __DIR__.'/vendor.php';

Route::prefix('products')->group(function (): void {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('{product}/similar', [ProductController::class, 'similar'])->name('products.similar');
});

Route::apiResource('products.reviews', ProductReviewController::class)
    ->only(['index', 'store', 'destroy'])
    ->parameters(['reviews' => 'review'])
    ->middlewareFor(['store', 'destroy'], ['auth:sanctum']);

Route::prefix('categories')->group(function (): void {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('{category}/products', [CategoryController::class, 'products'])->name('categories.products');
});

Route::prefix('tags')->group(function (): void {
    Route::get('/', [TagController::class, 'index'])->name('tags.index');
    Route::get('{tag}', [TagController::class, 'show'])->name('tags.show');
    Route::get('{tag}/products', [TagController::class, 'products'])->name('tags.products');
});

Route::prefix('vendors')->group(function (): void {
    Route::get('/', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('{vendorProfile}', [VendorController::class, 'show'])->name('vendors.show');
    Route::get('{vendorProfile}/products', [
        VendorController::class,
        'products',
    ])->name('vendors.products');
    Route::get('{vendorProfile}/short-videos', [
        VendorController::class,
        'shortVideos',
    ])->name('vendors.short-videos');
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{vendorProfile}/follow', [
            VendorController::class,
            'follow',
        ])->name('vendors.follow');
        Route::delete('{vendorProfile}/follow', [
            VendorController::class,
            'unfollow',
        ])->name('vendors.unfollow');
    });
});

Route::apiResource('vendors.reviews', VendorReviewController::class)
    ->only(['index', 'store', 'destroy'])
    ->parameters(['vendors' => 'vendorProfile', 'reviews' => 'review'])
    ->middlewareFor(['store', 'destroy'], ['auth:sanctum']);

Route::prefix('short-videos')->group(function (): void {
    Route::get('/', [ShortVideoController::class, 'index'])->name('short-videos.index');
    Route::get('{shortVideo}', [ShortVideoController::class, 'show'])->name('short-videos.show');
    Route::get('{shortVideo}/products', [
        ShortVideoController::class,
        'products',
    ])->name('short-videos.products');
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{shortVideo}/like', [ShortVideoController::class, 'like'])->name('short-videos.like');
        Route::post('{shortVideo}/save', [ShortVideoController::class, 'save'])->name('short-videos.save');
    });
});

Route::apiResource('short-videos.comments', ShortVideoCommentController::class)
    ->only(['index', 'store', 'destroy'])
    ->parameters(['comments' => 'comment'])
    ->middlewareFor(['store', 'destroy'], ['auth:sanctum']);

Route::prefix('livestreams')->group(function (): void {
    Route::get('/', [LivestreamController::class, 'index'])->name('livestreams.index');
    Route::get('{livestream}', [LivestreamController::class, 'show'])->name('livestreams.show');
    Route::get('{livestream}/products', [
        LivestreamController::class,
        'products',
    ])->name('livestreams.products.index');
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{livestream}/like', [LivestreamController::class, 'like'])->name('livestreams.like');
        Route::post('{livestream}/save', [LivestreamController::class, 'save'])->name('livestreams.save');
    });
    Route::get('{livestream}/subscriber-token', [
        LivestreamController::class,
        'subscriberToken',
    ])->name('livestreams.subscriber-token');
});

Route::apiResource('livestreams.comments', LivestreamCommentController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->parameters(['comments' => 'comment'])
    ->middlewareFor(['store', 'update', 'destroy'], ['auth:sanctum']);

Route::get('sections', [SectionController::class, 'index'])->name('sections.index');
Route::get('sliders', [SectionController::class, 'sliders'])->name('sliders.index');
Route::get('delivery-options', [DeliveryOptionController::class, 'index'])->name('delivery-options.index');
Route::get('shop-categories', [ShopCategoryController::class, 'index'])->name('shop-categories.index');
Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
Route::get('search', [SearchController::class, 'index'])->name('search.index');
