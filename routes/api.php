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
use App\Http\Controllers\Public\PaymentMethodController;
use App\Http\Controllers\Public\DeliveryOptionController;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [OTPAuthController::class, 'register']);
    Route::post('verify-otp', [OTPAuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [OTPAuthController::class, 'resendOtp']);
    Route::post('login', [OTPAuthController::class, 'login']);

    Route::prefix('social')->group(function (): void {
        Route::get('{provider}', [SocialAuthController::class, 'redirect']);
        Route::get('{provider}/callback', [
            SocialAuthController::class,
            'callback',
        ]);
    });

    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('logout', [SessionController::class, 'destroy']);
        Route::post('device-tokens', [DeviceTokenController::class, 'store']);
        Route::delete('device-tokens/{deviceToken}', [
            DeviceTokenController::class,
            'destroy',
        ]);
    });
});

require __DIR__.'/api/buyer.php';
require __DIR__.'/api/vendor.php';

Route::prefix('products')->group(function (): void {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{product}', [ProductController::class, 'show']);
    Route::get('{product}/similar', [ProductController::class, 'similar']);
    Route::get('{product}/reviews', [ProductController::class, 'reviews']);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{product}/reviews', [
            ProductController::class,
            'storeReview',
        ]);
        Route::delete('{product}/reviews/{review}', [
            ProductController::class,
            'destroyReview',
        ]);
    });
});

Route::prefix('categories')->group(function (): void {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('{category}', [CategoryController::class, 'show']);
    Route::get('{category}/products', [CategoryController::class, 'products']);
});

Route::prefix('tags')->group(function (): void {
    Route::get('/', [TagController::class, 'index']);
    Route::get('{tag}/products', [TagController::class, 'products']);
});

Route::prefix('vendors')->group(function (): void {
    Route::get('/', [VendorController::class, 'index']);
    Route::get('{vendorProfile}', [VendorController::class, 'show']);
    Route::get('{vendorProfile}/products', [
        VendorController::class,
        'products',
    ]);
    Route::get('{vendorProfile}/short-videos', [
        VendorController::class,
        'shortVideos',
    ]);
    Route::get('{vendorProfile}/reviews', [VendorController::class, 'reviews']);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{vendorProfile}/follow', [
            VendorController::class,
            'follow',
        ]);
        Route::delete('{vendorProfile}/follow', [
            VendorController::class,
            'unfollow',
        ]);
        Route::post('{vendorProfile}/reviews', [
            VendorController::class,
            'storeReview',
        ]);
        Route::delete('{vendorProfile}/reviews/{review}', [
            VendorController::class,
            'destroyReview',
        ]);
    });
});

Route::prefix('short-videos')->group(function (): void {
    Route::get('/', [ShortVideoController::class, 'index']);
    Route::get('{shortVideo}', [ShortVideoController::class, 'show']);
    Route::get('{shortVideo}/products', [
        ShortVideoController::class,
        'products',
    ]);
    Route::get('{shortVideo}/comments', [
        ShortVideoController::class,
        'comments',
    ]);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{shortVideo}/comments', [
            ShortVideoController::class,
            'storeComment',
        ]);
        Route::delete('{shortVideo}/comments/{comment}', [
            ShortVideoController::class,
            'destroyComment',
        ]);
        Route::post('{shortVideo}/like', [ShortVideoController::class, 'like']);
        Route::post('{shortVideo}/save', [ShortVideoController::class, 'save']);
    });
});

Route::middleware(['auth:sanctum'])->get('shorts/saved', [
    ShortVideoController::class,
    'saved',
]);

Route::prefix('livestreams')->group(function (): void {
    Route::get('/', [LivestreamController::class, 'index']);
    Route::get('{livestream}', [LivestreamController::class, 'show']);
    Route::get('{livestream}/products', [
        LivestreamController::class,
        'products',
    ]);
    Route::get('{livestream}/comments', [
        LivestreamController::class,
        'comments',
    ]);
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('{livestream}/comments', [
            LivestreamController::class,
            'storeComment',
        ]);
        Route::delete('{livestream}/comments/{comment}', [
            LivestreamController::class,
            'destroyComment',
        ]);
        Route::post('{livestream}/like', [LivestreamController::class, 'like']);
        Route::post('{livestream}/save', [LivestreamController::class, 'save']);
    });
    Route::get('{livestream}/subscriber-token', [
        LivestreamController::class,
        'subscriberToken',
    ]);
});

Route::middleware(['auth:sanctum'])
    ->prefix('lives')
    ->group(function (): void {
        Route::get('liked', [LivestreamController::class, 'liked']);
        Route::get('saved', [LivestreamController::class, 'saved']);
        Route::get('{livestream}/likes-count', [
            LivestreamController::class,
            'likesCount',
        ]);
    });

Route::get('sections', [SectionController::class, 'index']);
Route::get('sliders', [SectionController::class, 'sliders']);
Route::get('delivery-options', [DeliveryOptionController::class, 'index']);
Route::get('shop-categories', [ShopCategoryController::class, 'index']);
Route::get('payment-methods', [PaymentMethodController::class, 'index']);
Route::get('search', [SearchController::class, 'index']);
