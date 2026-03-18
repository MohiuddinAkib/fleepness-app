<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\AddressController;
use App\Http\Controllers\Me\ProfileController;
use App\Http\Controllers\Auth\OTPAuthController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Public\VendorController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\DeviceTokenController;
use App\Http\Controllers\Me\VendorProfileController;
use App\Http\Controllers\Me\PaymentAccountController;

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

    // Vendor follow/unfollow (auth required)
    Route::post('vendors/{vendorProfile}/follow', [VendorController::class, 'follow']);
    Route::delete('vendors/{vendorProfile}/follow', [VendorController::class, 'unfollow']);

    // Vendor reviews (auth required)
    Route::post('vendors/{vendorProfile}/reviews', [VendorController::class, 'storeReview']);
    Route::delete('vendors/{vendorProfile}/reviews/{review}', [VendorController::class, 'destroyReview']);
});

// Public routes
Route::get('vendors', [VendorController::class, 'index']);
Route::get('vendors/{vendorProfile}', [VendorController::class, 'show']);
Route::get('vendors/{vendorProfile}/reviews', [VendorController::class, 'reviews']);
