<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\Me\ProfileController;
use App\Http\Controllers\Public\TagController;
use App\Http\Controllers\Auth\OTPAuthController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Public\VendorController;
use App\Http\Controllers\Me\VendorOrderController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\SectionController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Auth\DeviceTokenController;
use App\Http\Controllers\Me\VendorProfileController;
use App\Http\Controllers\Me\PaymentAccountController;
use App\Http\Controllers\Public\LivestreamController;
use App\Http\Controllers\Public\ShortVideoController;
use App\Http\Controllers\Public\DeliveryOptionController;
use App\Http\Controllers\Me\LivestreamController as MeLivestreamController;
use App\Http\Controllers\Me\ShortVideoController as MeShortVideoController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;

// Auth
Route::prefix('auth')->group(function (): void {
    Route::post('register', [OTPAuthController::class, 'register']);
    Route::post('verify-otp', [OTPAuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [OTPAuthController::class, 'resendOtp']);
    Route::post('login', [OTPAuthController::class, 'login']);

    Route::get('social/{provider}', [SocialAuthController::class, 'redirect']);
    Route::get('social/{provider}/callback', [SocialAuthController::class, 'callback']);

    Route::middleware(['auth:sanctum', 'bind.user'])->group(function (): void {
        Route::post('logout', [SessionController::class, 'destroy']);
        Route::post('device-tokens', [DeviceTokenController::class, 'store']);
        Route::delete('device-tokens/{deviceToken}', [DeviceTokenController::class, 'destroy']);
    });
});

// Authenticated routes
Route::middleware(['auth:sanctum', 'bind.user'])->group(function (): void {
    // Own profile
    Route::get('me', [ProfileController::class, 'show']);
    Route::patch('me', [ProfileController::class, 'update']);

    // Own vendor profile
    Route::get('me/vendor', [VendorProfileController::class, 'show']);
    Route::patch('me/vendor', [VendorProfileController::class, 'update']);
    Route::get('me/balance', [VendorProfileController::class, 'balance']);

    // Addresses
    Route::get('me/addresses', [AddressController::class, 'index']);
    Route::post('me/addresses', [AddressController::class, 'store']);
    Route::patch('me/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('me/addresses/{address}', [AddressController::class, 'destroy']);
    Route::post('me/addresses/{address}/default', [AddressController::class, 'setDefault']);

    // Payment accounts
    Route::get('me/payment-accounts', [PaymentAccountController::class, 'index']);
    Route::post('me/payment-accounts', [PaymentAccountController::class, 'store']);
    Route::delete('me/payment-accounts/{paymentAccount}', [PaymentAccountController::class, 'destroy']);

    // Following
    Route::get('me/following', [FollowController::class, 'following']);

    // Vendor application
    Route::post('vendor-application', [VendorProfileController::class, 'apply']);
    Route::get('vendor-application/status', [VendorProfileController::class, 'applicationStatus']);

    // Vendor product management
    Route::get('vendor/products', [VendorProductController::class, 'index']);
    Route::post('vendor/products', [VendorProductController::class, 'store']);
    Route::get('vendor/products/{product}', [VendorProductController::class, 'show']);
    Route::patch('vendor/products/{product}', [VendorProductController::class, 'update']);
    Route::delete('vendor/products/{product}', [VendorProductController::class, 'destroy']);
    Route::post('vendor/products/{product}/toggle-status', [VendorProductController::class, 'toggleStatus']);

    // Vendor follow/unfollow (auth required)
    Route::post('vendors/{vendorProfile}/follow', [VendorController::class, 'follow']);
    Route::delete('vendors/{vendorProfile}/follow', [VendorController::class, 'unfollow']);

    // Vendor reviews (auth required)
    Route::post('vendors/{vendorProfile}/reviews', [VendorController::class, 'storeReview']);
    Route::delete('vendors/{vendorProfile}/reviews/{review}', [VendorController::class, 'destroyReview']);

    // Product reviews (auth required)
    Route::post('products/{product}/reviews', [ProductController::class, 'storeReview']);
    Route::delete('products/{product}/reviews/{review}', [ProductController::class, 'destroyReview']);

    // Cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/items', [CartController::class, 'store']);
    Route::patch('cart/items/{cartItem}', [CartController::class, 'update']);
    Route::delete('cart/items/{cartItem}', [CartController::class, 'destroy']);
    Route::get('cart/summary', [CartController::class, 'summary']);

    // Customer orders
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('me/orders', [OrderController::class, 'index']);
    Route::get('me/orders/{order}', [OrderController::class, 'show']);

    // Vendor orders
    Route::get('me/vendor-orders', [VendorOrderController::class, 'index']);
    Route::get('me/vendor-orders/{vendorOrder}', [VendorOrderController::class, 'show']);
    Route::patch('me/vendor-orders/{vendorOrder}/accept', [VendorOrderController::class, 'accept']);
    Route::patch('me/vendor-orders/{vendorOrder}/reject', [VendorOrderController::class, 'reject']);

    // Short videos (vendor CRUD + engagement)
    Route::get('me/short-videos', [MeShortVideoController::class, 'index']);
    Route::post('me/short-videos', [MeShortVideoController::class, 'store']);
    Route::patch('me/short-videos/{shortVideo}', [MeShortVideoController::class, 'update']);
    Route::delete('me/short-videos/{shortVideo}', [MeShortVideoController::class, 'destroy']);

    // Short video engagement (auth required)
    Route::post('short-videos/{shortVideo}/comments', [ShortVideoController::class, 'storeComment']);
    Route::delete('short-videos/{shortVideo}/comments/{comment}', [ShortVideoController::class, 'destroyComment']);
    Route::post('short-videos/{shortVideo}/like', [ShortVideoController::class, 'like']);
    Route::post('short-videos/{shortVideo}/save', [ShortVideoController::class, 'save']);

    // Livestreams (vendor CRUD + engagement)
    Route::get('me/livestreams', [MeLivestreamController::class, 'index']);
    Route::post('me/livestreams', [MeLivestreamController::class, 'store']);
    Route::patch('me/livestreams/{livestream}', [MeLivestreamController::class, 'update']);
    Route::delete('me/livestreams/{livestream}', [MeLivestreamController::class, 'destroy']);

    // Livestream engagement (auth required)
    Route::post('livestreams/{livestream}/comments', [LivestreamController::class, 'storeComment']);
    Route::delete('livestreams/{livestream}/comments/{comment}', [LivestreamController::class, 'destroyComment']);
    Route::post('livestreams/{livestream}/like', [LivestreamController::class, 'like']);
    Route::post('livestreams/{livestream}/save', [LivestreamController::class, 'save']);
});

// Public routes
Route::get('vendors', [VendorController::class, 'index']);
Route::get('vendors/{vendorProfile}', [VendorController::class, 'show']);
Route::get('vendors/{vendorProfile}/reviews', [VendorController::class, 'reviews']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('products/{product}/reviews', [ProductController::class, 'reviews']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::get('tags', [TagController::class, 'index']);

Route::get('sections', [SectionController::class, 'index']);
Route::get('sliders', [SectionController::class, 'sliders']);

Route::get('delivery-options', [DeliveryOptionController::class, 'index']);

// Short videos (public)
Route::get('short-videos', [ShortVideoController::class, 'index']);
Route::get('short-videos/{shortVideo}', [ShortVideoController::class, 'show']);
Route::get('short-videos/{shortVideo}/comments', [ShortVideoController::class, 'comments']);

// Livestreams (public)
Route::get('livestreams', [LivestreamController::class, 'index']);
Route::get('livestreams/{livestream}', [LivestreamController::class, 'show']);
Route::get('livestreams/{livestream}/comments', [LivestreamController::class, 'comments']);
